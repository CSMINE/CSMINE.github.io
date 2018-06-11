<?php

namespace App\Http\Controllers;

use App\Bet;
use App\Game;
use App\Item;
use App\Services\SteamItem;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReferalController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
	
	const ADD_MONEY_MAIN_REF = 5;		#Деньги пришедший
    const ADD_MONEY_REF = 4;            #Деньги пригласившего
	const HAVE_BET_CHECK = 1;			#Проверка на ставку 1-вкл. 0-выкл.

	public function ref()
	{
		$referals = \DB::table('refers')->join('users', 'refers.usera', '=', 'users.steamid64')->where('refers.userb', $this->user->steamid64)->get();
		$namekode = User::where('steamid64', $this->user->steamid64)->first(); //(Проверяем кастомный код)
		if ($namekode->refkode == NULL){$myref = $this->user->steamid64;} else {$myref = $namekode->refkode;}
		return view('pages.ref', compact('referals', 'myref'));
	}

	public function getcoupon(Request $request)
	{
	    $kode = $request->get('ref_get'); //(ID пригласившего)
	    $id = $this->user->steamid64; //(ID активирующего)
		$namekode = User::where('refkode', $kode)->first(); //(Проверяем кастомный код)
        
		if ($namekode == NULL ){
			$userp = User::where('refkode', $kode)->first();//(Пользователь пригласивший)
		} else {
			$userp = $namekode; //(Пользователь пригласивший)
		}
        
	    $usera = User::where('steamid64', $id)->first();//(Пользователь активирущий)
        
		if ($kode == $id || $kode == $usera->refkode) {
	        return [
                'type' => 'error', 
                'msg' => 'Вы не можете активировать свой код!'
            ];
	    }
        
	    if ($userp == NULL || $usera == NULL) {
			return [
                'type' => 'error', 
                'msg' => 'Один из пользователей не существует!'
            ];
	    }
        
		$havebet = \DB::table('bets')->where('user_id', '=', $usera->id)->first();
        
		if ($havebet == NULL && self::HAVE_BET_CHECK == 1 ){
			return [
                'type' => 'error', 
                'msg' => 'Вы должны сделать 1 ставку!'
            ];
		}
        
        if(GameController::CheckCSGO($this->user) == 'false'){
            return [
                'type' => 'error', 
                'msg' => 'Для активаций нужна CS:GO!'
            ];
        }
        
	    $sameref = \DB::table('refers')->where('usera', '=', $id)->where('userb', '=', $userp->steamid64)->orderBy('id', 'desc')->get(); //(Проверяем на наличие рефера)
        
	    if ($sameref) {
			return [
                'type' => 'error', 
                'msg' => 'Такой рефер уже есть!'
            ];
	    }
        
	    $gmoneyp = $userp->money + self::ADD_MONEY_REF; //(Деньги пригласившего)
	    $gmoneya = $usera->money + self::ADD_MONEY_MAIN_REF; //(Деньги активирующего)
	    $kodescore = $userp->refcount + 1; //(Очки пригласившего)	   
	    $kodestatus = $userp->refprofit + self::ADD_MONEY_REF; //(Прибыль пригласившего)	
	    $idstatus = $usera->refprofit + self::ADD_MONEY_MAIN_REF; //(Прибыль активирующего)	
	    $firstuse = $usera->refstatus; //(Активирован ли рефер у активирующего)	
	    if ($firstuse > 0) {
			return [
                'type' => 'error', 
                'msg' => 'Реферальный код уже использован!'
            ];
	    }
		\DB::table('users')->where('steamid64', $userp->steamid64)->update(['money' => $gmoneyp]);
		\DB::table('users')->where('steamid64', $id)->update(['money' => $gmoneya]);
		\DB::table('users')->where('steamid64', $id)->update(['refstatus' => 1]);
		\DB::table('users')->where('steamid64', $userp->steamid64)->update(['refcount' => $kodescore]);
		\DB::table('users')->where('steamid64', $userp->steamid64)->update(['refprofit' => $kodestatus]);
		\DB::table('users')->where('steamid64', $id)->update(['refprofit' => $idstatus]);
		\DB::table('refers')->insertGetId(['usera' => $id, 'userb' => $userp->steamid64]);
        
        $this->redis->publish('updateBalance', json_encode([
            'user' => $this->user->steamid64,
            'balance' => $this->user->money
        ]));
        
		return [
            'type' => 'success', 
            'msg' => 'Реферальный код активирован!'];
	}
	
	public function setcoupon(Request $request)
	{
	    $kode = $request->get('ref_create'); //(Код)
	    $id = $this->user->steamid64; //(ID меняющего)
		$kode = strtolower($kode);
        
		if (preg_match("/^[0-9a-z]+$/i", $kode)) {} else {
			return [
                'type' => 'error', 
                'msg' => 'Вы ввели не верный код!'
            ];  
		}
        
		$kode = strtoupper($kode);
		$kodeexist = User::where('refkode', $kode)->first();//(Существует ли код?)
	    $usera = User::where('steamid64', $id)->first();//(Пользователь меняющий)
		
	    if ($kodeexist != NULL || $usera == NULL) {
	        return [
                'type' => 'error', 
                'msg' => 'Код уже существует!'];
	    }
		
	    if ($kodeexist == $kode) {
	        return [
                'type' => 'error', 
                'msg' => 'Код уже существует!'];
	    }
		\DB::table('users')->where('steamid64', $id)->update(['refkode' => $kode]);
		
		return [
                'type' => 'success', 
                'msg' => 'Код сохранен!'
        ];
	}
}
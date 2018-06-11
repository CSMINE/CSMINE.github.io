<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\Bet;
use App\Config;
use App\User;
use App\Game;
use App\Bots;
use App\Items;
use App\Promocode;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use File;


class Admin extends Controller
{
	public $config;
	
    public function __construct()
    {
        parent::__construct();
        $this->config = Config::find(1);
    }
	
    public static function curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    public function index()
    {
		$configs = Config::where('id', 1)->first();
        return view('admin.settings', compact('configs'));
    }
    
    public function game(){
        
        $game = Game::with(['bets', 'winner'])->orderBy('id', 'desc')->get();
    
        return view('admin.game.game', compact('game'));
    }
	
    public function edit_site(Request $r) {     
        Config::where('id', 1)->update([
            'sitename' => $r->get('sitename'),
            'description' => $r->get('description'),
            'socket_ip' => $r->get('socket_ip'),
            'keywords' => $r->get('keywords'),
            'curs' => $r->get('curs'),
            'curs_2' => $r->get('curs_2'),
            'online' => $r->get('online'),
            'min_dep_sum' => $r->get('min_dep_sum'),
            'steamapikey' => $r->get('steamapikey'),
            'mrh_ID' => $r->get('mrh_ID'),
            'mrh_secret1' => $r->get('mrh_secret1'),
            'mrh_secret2' => $r->get('mrh_secret2'),
            'privatekey' => $r->get('privatekey'),
            'publickey' => $r->get('publickey')
        ]);
        return [
            'msg' => 'Настройки успешно сохранены!',
            'icon' => 'success'
        ];
    }
	
	public function edit_user(Request $r) {     
        User::where('id', $r->get('id'))->update([
            'trade_link' => $r->get('trade_link'),
            'money' => $r->get('money'),
            'is_admin' => $r->get('is_admin'),
            'banchat' => $r->get('banned')
        ]);
        return [
            'msg' => 'Пользователь успешно отредактирован!',
            'icon' => 'success'
        ];
    }

    public function users()
    {
        $user = User::get();
        return view('admin.users.users',compact('user'));
    }

    public function user($id)
    {
        $user = User::find($id);
        return view('admin.users.user',compact('user'));
    }    
    public function edit_game(Request $r){
        
        $game = Game::with(['winner'])->where('status', Game::STATUS_FINISHED)->where('id', $r->get('id'))->first();
        $won_items = json_decode($game->won_items);
        $returnItems = [];
        
        /*return $game->won_items;*/
        
        
        
        foreach($won_items as $item){
                $returnItems[] = $item->classid;
        }
    
/*        $value = [
            'appId' => config('mod_game.appid'),
            'steamid' => $game->winner->steamid64,
            'accessToken' => $game->winner->accessToken,
            'items' => $returnItems,
            'game' => $r->get('id')
        ];*/
        
        /*$this->redis->rpush(self::SEND_OFFERS_LIST, json_encode($value));*/
        
/*       return [
            'msg' => 'Трейд успешно отправлен!',
            'icon' => 'success'
        ];*/
    }
    
}
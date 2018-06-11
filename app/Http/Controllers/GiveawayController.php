<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Item;
use App\Services\SteamItem;
use App\Giveaway;
use App\Giveaway_items;
use App\Services\RandomOrgClient;
class GiveawayController extends Controller
{
    const MIN                           = '35';
    const MAX                           = '50';
    const itemstogiveaway               = 'items.to.giveaway';
    const APPID                         = '730';
    const SEND_OFFERS_LIST_GIVEAWAY     = 'send.offers.list.giveaway';
    const END_GIVEAWAY_LIST             = 'end.giveaway.list';
  

    public function __construct()
    {
        parent::__construct();
        $this->giveaway = $this->getLastGame();
    }

    public function accept()
    {
        $userid = htmlspecialchars_decode($this->user->id);
        $givegame = \DB::table('giveaway_users')->where('giveaway_id', $this->giveaway->id)->where('user_id', $userid)->count();
        if(!strstr(strtolower($this->user->username), strtolower(config('app.sitename')))){
            $result = response()->json([ 'status' => 'error', 'msg' => 'Добавьте в ник название нашего сайта - '.config('app.sitename')]); 
        } else if($givegame > NULL) {
            $result = response()->json([ 'status' => 'error', 'msg' => 'Вы уже учавствуете в розыгрыше!']); 
            
        } else {
            $lastnum = \DB::table('giveaway_users')->where('giveaway_id', $this->giveaway->id)->orderBy('id', 'ask')->take(1)->pluck('num');
            if ($lastnum == NULL) {
                $num = 1;
            } else {
                $num = $lastnum + 1;
            }
            \DB::table('giveaway_users')->insert(['user_id' => $userid , 'giveaway_id' => $this->giveaway->id , 'num' => $num]);
            $result = response()->json([ 'status' => 'success','msg' => 'Вы приняли участие в розыгрыше!']);    
        }
            $giveaway_users = \DB::table('giveaway_users')
            ->where('giveaway_id', $this->giveaway->id)
            ->join('users', 'giveaway_users.user_id', '=', 'users.id')
            ->get();
        $max_user = $this->giveaway->max_user;
        $all_user = count($giveaway_users);
        if($max_user == $all_user){
            $this->end_giveaway();
        }
        return $result;
    }
    public function new_giveaway()
    {
		$kolvo=\DB::table('giveaway_items')->where('status',0)->orderBy('id', 'desc')->count();
		if ($kolvo >= 1) {
		$price=\DB::table('giveaway_items')->where('status',0)->orderBy('id', 'desc')->take(1)->pluck('price');
        $max_users = round($price) + mt_rand(1, 7);
        $betitem = \DB::table('giveaway_items')->where('status',0)->orderBy('id', 'desc')->take(1)->get();
        $betitem_id = \DB::table('giveaway_items')->where('status',0)->orderBy('id', 'desc')->take(1)->pluck('id');
        \DB::table('giveaway_items')->where('id',$betitem_id)->update(['status' => 3]);
        $giveaway = Giveaway::create(['max_user' => $max_users, 'items' => json_encode($betitem)]);
        $this->redis->rpush(self::END_GIVEAWAY_LIST, json_encode($giveaway));
        return $giveaway;
        } else{
		
	   }
    }
    public function end_giveaway()
    {
        $max_user = $this->giveaway->max_user;
        $winner_num = mt_rand(1, $max_user);
        $winner_id = \DB::table('giveaway_users')->where('giveaway_id',$this->giveaway->id)->where('num',$winner_num)->pluck('user_id');
        $winner = User::where('id', $winner_id)->first();
        $this->giveaway->winner_id         	= $winner_id;
        $this->giveaway->finished_at    	= Carbon::now();
        $this->giveaway->status    			= 4;
        $this->giveaway->save();

        $this->sendItems(json_decode($this->giveaway->items),$winner);
        $this->new_giveaway();
    }
    
    public function getLastGame()
    {
        $giveaway = Giveaway::orderBy('id', 'desc')->first();
        if(is_null($giveaway)) $giveaway = $this->new_giveaway();
        return $giveaway;
    }
    public function addItemsToGiveaway()
    {
        $jsonItems = $this->redis->lrange(self::itemstogiveaway, 0, -1);
        foreach($jsonItems as $jsonItem){
            $items = json_decode($jsonItem, true);
            foreach($items as $item) {
                $dbItemInfo = Item::where('market_hash_name', $item['market_hash_name'])->first();
                if (is_null($dbItemInfo)) {
                    $itemInfo = new SteamItem($item);
                    $item['price'] = $itemInfo->price;
                    Giveaway_items::create($item);
                }else{
                    $item['price'] = $dbItemInfo->price;
                    Giveaway_items::create($item);
                }
            }
            $this->redis->lrem(self::itemstogiveaway, 1, $jsonItem);
        }
        return response()->json(['success' => true]);
    }  
    public function sendItems($item, $user)
    {
        $value = [
            'appId' => self::APPID,
            'steamid' => $user->steamid64,
            'accessToken' => $user->accessToken,
            'items' => $item
        ];

        $this->redis->rpush(self::SEND_OFFERS_LIST_GIVEAWAY, json_encode($value));
        return $item;
    }
    public function setGameStatusGiveaway(Request $request)
    {
        $this->giveaway->status = $request->get('status');
        $this->giveaway->save();
        return $this->giveaway;
    }
}

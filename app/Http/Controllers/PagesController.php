<?php

namespace App\Http\Controllers;

use App\Bet;
use App\Game;
use App\Game_double;
use App\Item;
use App\Services\SteamItem;
use App\User;
use Illuminate\Http\Request;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\Http\Controllers\ConfigController;
use App\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use DB;

class PagesController extends Controller
{

    public function __construct() {
		parent::__construct();
        $this->config = new ConfigController();
        $this->config = $this->config->config;
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
    
    public function about()
    {
        return view('pages.about');
    }
	
	public function support()
    {
        return view('pages.support');
    }
	
	public function skinsDeposit()
    {
        return view('pages.skinsDeposit');
    }
	
    public function top()
    {
        $users = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.steamid64',
				'users.is_admin',
                \DB::raw('SUM(games.price) as top_value'),
                \DB::raw('COUNT(games.id) as wins_count')
            )
            ->join('games', 'games.winner_id', '=', 'users.id')
            ->groupBy('users.id')
            ->orderBy('top_value', 'desc')
            ->limit(21)
            ->get();
        $place = 1;
        $i = 0;
		foreach($users as $u){
			$users[$i]->games_played = count(\DB::table('games')
				->join('bets', 'games.id', '=', 'bets.game_id')
				->where('bets.user_id', $u->id)
				->groupBy('bets.game_id')
				->select('bets.id')->get());
			$users[$i]->win_rate = round($users[$i]->wins_count / $users[$i]->games_played, 3) * 100;
			$i++;
		}
        return view('pages.top', compact('users', 'place'));
    }


	public static function sortByChance($chances)
    {
        usort($chances, function ($a, $b) {
            $a1 = $a->chance;
            $b1 = $b->chance;
            return $a1 < $b1;
        });

        return $chances;
    }
	
    public function history()
    {
        $games = Game::with(['bets', 'winner'])->where('status', Game::STATUS_FINISHED)->orderBy('id', 'desc')->paginate(49);
        $games_double = Game_double::where('status', Game::STATUS_FINISHED)->orderBy('id', 'desc')->paginate(49);


        return view('pages.history', compact('games','games_double'));
        
    }
    
    public function test(){
    
      
            $link = 'http://steamcommunity.com/gid/103582791429521412/memberslistxml/?xml=1';
        	$str  = file_get_contents($link);
        
            preg_match('#<memberList>.*?.<members>(.*?)</members>.*?</memberList>#is', $str, $value);
            
            $gid = $value[1];
        
            
            
            return $gid;
        
    }
    
    public function game($gameId)
    {
        
        if(isset($gameId) && Game::where('status', Game::STATUS_FINISHED)->where('id', $gameId)->count()){
            $game = Game::with(['winner'])->where('status', Game::STATUS_FINISHED)->where('id', $gameId)->first();
            $game->ticket = floor($game->rand_number * ($game->price * 100));
            $bets = $game->bets()->with(['user','game'])->get()->sortByDesc('created_at');

            $commission_items_bd = \DB::table('games')->where('status', Game::STATUS_FINISHED)->where('id', $gameId)->first();
            $commission_array = ['game'];

            if ($commission_items_bd->commission_items != "") {
            $commission_array = [];
            $comItems = json_decode($commission_items_bd->commission_items);
                foreach ($comItems as $commiss) {
                    if ($commiss->market_hash_name != "") {
                    $commission_array[] = $commiss->classid;
                    } else {
                    $commission_array[] = $commiss->id;
                    }
                }
            }
            $gg = [];
            $items = [];
            $bett = (object)[];
            foreach ($bets as $bet) {
                foreach (json_decode($bet->items) as $i) {
                    if (property_exists($i, 'id')) {
                        if (in_array($i->id, $commission_array)) {
                            $value = array_search($i->id, $commission_array);
                            unset($commission_array[$value]);
                            $items[] = [
                            'id' => 'commission',
                            'name' => $i->name,
                            'img' => $i->img,
                            'price' => $i->price,
                            ];
                        } else {
                            $items[] = [
                            'id' => $i->id,
                            'name' => $i->name,
                            'img' => $i->img,
                            'price' => $i->price,
                            ];
                        }
                    } else {
                    if (in_array($i->classid, $commission_array)) {
                        $value = array_search($i->classid, $commission_array);
                        unset($commission_array[$value]);
                        $items[] = [
                        'name' => $i->name,
                        'market_hash_name' => 'commission',
                        'classid' => $i->classid,
                        'rarity' => $i->rarity,
                        'price' => $i->price,
                        ];
                    } else {
                        $items[] = [
                        'name' => $i->name,
                        'market_hash_name' => $i->market_hash_name,
                        'classid' => $i->classid,
                        'rarity' => $i->rarity,
                        'price' => $i->price,
                        ];
                    }
                    }
                }
                $bett->id = $bet->id;
                $bett->user_id = $bet->user_id;
                $bett->game_id = $bet->game_id;
                $bett->items = json_encode($items);
                $bett->itemsCount = $bet->itemsCount;
                $bett->price = $bet->price;
                $bett->place = $bet->place;
                $bett->from = $bet->from;
                $bett->to = $bet->to;
                $bett->user = $bet->user;
                $bett->game = $bet->game;
                $gg[] = $bett;
                $items = [];
                $bett = (object)[];
            }
            $chances = [];
            $commission = 1;
            return view('pages.game', compact('kolvo','game', 'bets', 'chances','user_items','giveaway','giveaway_users','commission','commission_array', 'gg'));;
        }
        return redirect()->route('index');
    }

    public static function time_ago($time_ago){
        $timeago = strval($time_ago);
        $time = strtotime($timeago);
        $cur_time 	= time();
        $time_elapsed 	= $cur_time - $time;
        $seconds 	= $time_elapsed ;
        $minutes 	= round($time_elapsed / 60 );
        $hours 		= round($time_elapsed / 3600);
        $days 		= round($time_elapsed / 86400 );

        if($seconds <= 60){
            return "$seconds секунд назад";
        }

        else if($minutes <= 60)
        {
            if($minutes == 1 || $minutes == 21 || $minutes == 31 || $minutes == 41 || $minutes == 51){
                return "$minutes минуту назад";
            }
            elseif($minutes < 5){
                return "$minutes минуты назад";
            }
            else{
                return "$minutes минут назад";
            }

        }

        else if($hours <= 24)
        {
            if($hours==1 || $hours == 21) {
                return "$hours час назад";
            }
            elseif($hours < 5) {
                return "$hours часа назад";
            }
            else{
                return "$hours часов назад";
            }
        }

        else if($days <= 7)
        {
            if($days == 1) {
                return "$days день назад";
            }
            elseif($days < 5){
                return "$days дня назад";
            }
            else{
                return "$days дней назад";
            }
        }

    }
    
/*Пополнение*/
    public function pay(Request $r)
	{
        $sum = $r->get('num');
		
		if(!$sum) return Redirect::back();
        /*CREATE PAY*/
        $pay = [
            'secret' => md5($this->config->mrh_ID . ":" . $sum . ":" . $this->config->mrh_secret1 . ":" . $this->config->order_id),
            'merchant_id' => $this->config->mrh_ID,
            'order_id' => $this->config->order_id,
            'sum' => $r->get('num'),
            'user_id' => $this->user->steamid64
        ];
        DB::table('payments')->insert($pay);
        /*REDIRECT*/
        
        DB::table('config')->where('id', 1)->update([
            'order_id' => $this->config->order_id+1 
        ]);
        
        return Redirect('https://www.free-kassa.ru/merchant/cash.php?m='.$this->config->mrh_ID.'&oa='.$r->get('num').'&o='.$pay['order_id'].'&s='.md5($this->config->mrh_ID.':'.$sum.':'.$this->config->mrh_secret1.':'.$pay['order_id']));
        
	}
	
	public function result(Request $r)
	{
        $ip = false;
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $this->getIp($_SERVER['HTTP_X_REAL_IP']);
        } else {
            $ip = $this->getIp($_SERVER['REMOTE_ADDR']);
        }
        if(!$ip) return 'Ошибка при проверке IP free-kassa';
        /* SEARCH MERCH */
        $merch = DB::table('payments')->where('order_id', $r->get('MERCHANT_ORDER_ID'))->first();
		$merch_order_id = $r->get('MERCHANT_ORDER_ID');
        if(!$merch) return 'Не удалось найти заказ #'.$r->get('MERCHANT_ORDER_ID');
        /* ADD BALANCE TO USER */
        #check amount
        if($r->get('AMOUNT') != $merch->sum) return 'Вы оплатили не тот заказ!';
        
        $user = User::where('steamid64', $merch->user_id)->first();
        if(!$user) return 'Не удалось найти юзера!';
        
        $sum = round($merch->sum);

        /**/
        
        User::where('steamid64', $user->steamid64)->update([
            'money' => $user->money+$sum 
        ]);
        
        /*UPDATE MERCH STATUS*/
        
        DB::table('payments')->where('order_id', $merch_order_id)->update([
            'status' => 1 
        ]);
		
		DB::table('depoffers')->insert([
        	'user' => $user->steamid64,
            'price' => $sum,
            'status' => 1,
            'type' => '2'
       	]);
        
        /* SUCCESS REDIRECT */
        return redirect()->route('index');
	}
    
    /* CHECK FREE KASSA IP */
    function getIp($ip) {
        $list = ['136.243.38.147','136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98','178.32.77.0','178.32.77.1'];
        for($i = 0; $i < count($list); $i++) {
            if($list[$i] == $ip) return true;
        }
        return false;
    }
	
	function success() {
		return view('pages.pay.success');
	}
	
	function fail() {
		return view('pages.pay.fail');
	}
    /*Пополнение*/
    
    public function genxren(){
        
        $query = array(
          'cod' => 'TJFH9',
          'key' => '968724fd8fgf55810e24d99be3a776c70',
          'userid' => '76561198372881370'
        );   
        
        $password = hash_hmac('sha1', $this->getSignString($query), $this->config->privatekey);
        
        return $password;
            
    }
    
    /*Пополнение скинами*/
    public function skinpay()
	{

        $query = array(
          'orderid' => $this->config->order_id,
          'key' => $this->config->publickey,
          'userid' => $this->user->steamid64
        );    
            
        /*CREATE PAY*/
        $pay = [
            'key'=> $this->config->publickey,
            'orderid' => $this->config->order_id,
            'userid' => $this->user->steamid64,
            'sign' => hash_hmac('sha1', $this->getSignString($query), $this->config->privatekey)
        ];
            
        DB::table('payments_skins')->insert($pay);
        
        
        DB::table('config')->where('id', 1)->update([
            'order_id' => $this->config->order_id+1 
        ]);
        
        return Redirect('https://skinpay.com/deposit/?key='. $this->config->publickey .'&orderid='.$pay['orderid'].'&userid='.$pay['userid'].'&sign='.$pay['sign'].'');
        
	}
    
    public function getSignString($q) {
      ksort($q);
      $paramsString = '';
      foreach ($q as $key => $value) {
        if($key == 'sign') continue;
        $paramsString .= $key .':'. $value .';';
      }
      return $paramsString;
    }
    
    public function pushback(Request $r){
        if($r->get('status') == 'success')  {  
            /* SEARCH MERCH */
            $merch = DB::table('payments_skins')->where('orderid', $r->get('orderid'))->first();
            $merch_order_id = $r->get('orderid');
            if(!$merch) return 'Не удалось найти заказ #'.$r->get('orderid');
            /* ADD BALANCE TO USER */
            #check amount
            $user = User::where('steamid64', $merch->userid)->first();
            if(!$user) return 'Не удалось найти юзера!';
            /**/

            User::where('steamid64', $user->steamid64)->update([
                'money' => $user->money + ($r->get('amount') / 100)
            ]);

            /*UPDATE MERCH STATUS*/

            DB::table('payments_skins')->where('orderid', $merch_order_id)->update([
                'status' => 1 
            ]);
            return  "OK";
        }
    }
    /*Пополнение скинами конц*/
}

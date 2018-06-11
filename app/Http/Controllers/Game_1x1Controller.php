<?php

namespace App\Http\Controllers;
use App\Bet_1x1;
use App\Game_1x1;
use App\Game;
use DB;
use App\Item;
use App\Services\SteamItem;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Game_1x1Controller extends Controller
{
    const MIN_PRICE     = 60;
    const MAX_PRICE     = 900;
    const MAX_ITEMS     = 10;
    const MIN_USERS     = 3;   
    const COMMISSION    = 10; 
    const MIN_COMISSION_PRICE = 7;             # Минимальная цена одного предмета для комисии
    const SEND_OFFERS_LIST = 'send.offers.list.1x1';
    const BET_DECLINE_CHANNEL = 'depositDecline';
    const INFO_CHANNEL = 'msgChannel';
    const NEW_BET_CHANNEL = 'newDeposit_1x1';
    const SHOW_WINNERS = 'show.winners_1x1';
        public function __construct()
    {
        parent::__construct();
        $this->game1x1 = $this->getLastGame();
        $this->lastTicket1x1 = $this->redis->get('last.ticket.' . $this->game1x1->id . '.1x1');
        if(is_null($this->lastTicket1x1)) $this->lastTicket1x1 = 0;
    }
    public $game1x1;

    protected $lastTicket = 0;

    private static $chances_cache = [];
    public function currentGame()
    {
        $game1x11 = Game_1x1::orderBy('id', 'desc')->first();
        $percents = $this->_getChancesOfGame($game1x11, true);
        $this->_sortArrayByKey($percents, 'chance');
        $bets = DB::table('bets_1x1')
        ->where('game_id', $this->game1x1->id)
        ->join('users', 'bets_1x1.user_id', '=', 'users.id')
        ->get();
        $users_1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 1)->pluck('user_id');
        $user_1 = User::find($users_1);
        $users_2 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 2)->pluck('user_id');
        $user_2 = User::find($users_2);
        $users_3 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 3)->pluck('user_id');
        $user_3 = User::find($users_3);
        if (empty($user_1->steamid64)) {
            $user_steam_1 = 1;
        } else {
            $user_steam_1 = $user_1->steamid64;
        }
        if (empty($user_2->steamid64)) {
            $user_steam_2 = 2;
        } else {
            $user_steam_2 = $user_2->steamid64;
        }
        if (empty($user_3->steamid64)) {
            $user_steam_3 = 3;
        } else {
            $user_steam_3 = $user_3->steamid64;
        }
        $bet1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 1)->pluck('id');
        $bet2 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 2)->pluck('id');
        $bet3 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 3)->pluck('id');
        $user_avatar_1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 1)->pluck('avatar');
        $user_avatar_2 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 2)->pluck('avatar');
        $user_avatar_3 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 3)->pluck('avatar');
        $items_1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 1)->first();
        $items_2 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 2)->first();
        $items_3 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 3)->first();
        $chance_1 = $this->_getUserChanceOfGame($user_1, $game1x11); 
        #LAST WINNER#
        $games = \DB::table('games_1x1')->where('status', Game::STATUS_FINISHED)->count();
        if($games >= 1) {
        $last_winner  = \DB::table('games_1x1')->where('status' , '=', 3)->orderBy('id', 'desc')->take(1)->get();
    
        foreach($last_winner as $last){

        $user = User::find($last->winner_id);
        $lastname = $user->username;
        $lastava = $user->avatar;
        $lastchanc = $last->chance;
        $lastprice = $last->price;

        }
        }else{
        $lastname = 'Ещё не выбран';
        $lastava = '/front/images/unknown.jpg';
        $lastchanc = '???';
        $lastprice = '???'; 
        }
        #LAST WINNER#
        $chance_2 = $this->_getUserChanceOfGame($user_2, $game1x11); 
        $chance_3 = $this->_getUserChanceOfGame($user_3, $game1x11); 
        $price_1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 1)->pluck('price');
        $price_2 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 2)->pluck('price');
        $price_3 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('number', 3)->pluck('price');
        if(!is_null($this->user)) $user_items = $this->user->itemsCountByGame($game1x11);
        $games = \DB::table('games_1x1')->orderBy('id', 'desc')->skip(1)->take(7)->get();
        foreach($games as $game1x1){
        $game1x1->users = \DB::table('games_1x1')
            ->join('bets_1x1', 'games_1x1.id', '=', 'bets_1x1.game_id')
            ->join('users', 'bets_1x1.user_id', '=', 'users.id')
            ->where('games_1x1.id', $game1x1->id)
            ->groupBy('users.username')
            ->select('users.*','bets_1x1.number')
            ->get();
            $game1x1->winner = \DB::table('users')
            ->where('users.id', $game1x1->winner_id)
            ->join('bets_1x1', 'users.id', '=', 'bets_1x1.user_id')
            ->where('bets_1x1.game_id', $game1x1->id)
            ->select('users.*','bets_1x1.number')
            ->first();
            $game1x1->loser = \DB::table('users')
            ->where('users.id', $game1x1->loser_id)
            ->join('bets_1x1', 'users.id', '=', 'bets_1x1.user_id')
            ->where('bets_1x1.game_id', $game1x1->id)
            ->select('users.*','bets_1x1.number')
            ->first();
            $game1x1->bet1 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 1)->first();
            $game1x1->bet2 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 2)->first();
            $game1x1->bet3 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 3)->first();
            $game1x1->price1 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 1)->pluck('price');
            $game1x1->price2 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 2)->pluck('price');
            $game1x1->price3 = \DB::table('bets_1x1')->where('game_id', $game1x1->id)->where('number', 3)->pluck('price');
     		$game1x1->chance1 = round($game1x1->price1 / $game1x1->price, 3) * 100;
     		$game1x1->chance2 = round($game1x1->price2 / $game1x1->price, 3) * 100;
     		$game1x1->chance3 = round($game1x1->price3 / $game1x1->price, 3) * 100;
     		$game1x1->win_chance1 = $this->_getUserChanceOfGame($game1x1->winner, $game1x1);
        }
        return view('pages.1x1', compact('game1x11','bets','user_avatar_2','user_avatar_1','user_avatar_3','items_1','items_2','items_3','price_1','price_2','price_3','chance_1','chance_2','chance_3','user_steam_1','user_steam_2','user_steam_3','games','bet1','bet2','bet3','users_1','users_2','users_3'));
    }
    public static function _getUserChanceOfGame1($price, $user, $bet)
    {
        $chance = 0;
        if (!is_null($user)) {
                if ($bet == 0) {
                    $chance = 0;
                } else {
                    $chance = round($bet / $price, 3) * 100;
                }
        }
        return $chance;
    }
    public function setWinner(Request $request)
    {
        $id = $request->get('id');
        $user = User::where('id', $id)->first();
        $this->game1x1->winner_id = $id;
        $this->game1x1->save();
        return $user->username;
    }
    public function checkOffer()
    {
        $data = $this->redis->lrange('check.list.1x1', 0, -1);
        foreach($data as $offerJson) {
            $offer = json_decode($offerJson);
            $accountID = $offer->accountid;
            $items = json_decode($offer->items, true);
            $itemsCount = count($items);

            $user = User::where('steamid64', $accountID)->first();
    
            if (is_null($user)) {
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }else{
                if(empty($user->accessToken)){
                    $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                    $this->redis->lrem('check.list.1x1', 0, $offerJson);
                    $this->redis->rpush('decline.list.1x1', $offer->offerid);
                    $this->_responseErrorToSite('Введите трейд ссылку!', $accountID, self::BET_DECLINE_CHANNEL);
                    continue;
                }
            }
            $number1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('user_id', $user->id)->count();
            if ($number1 >= 1) {
                $this->_responseErrorToSite('Вы уже вносили в эту игру вещи', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }

            $totalItems = $user->itemsCountByGame($this->game1x1);
            if ($itemsCount > self::MAX_ITEMS || $totalItems > self::MAX_ITEMS || ($itemsCount+$totalItems) > self::MAX_ITEMS) {
                $this->_responseErrorToSite('Максимальное кол-во предметов для депозита - ' . self::MAX_ITEMS, $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }

            $total_price = $this->_parseItems($items, $missing, $price);

            if ($missing) {
                $this->_responseErrorToSite('Принимаются только предметы из CS:GO', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }

            if ($price) {
                $this->_responseErrorToSite('Невозможно определить цену одного из предметов', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }

            if ($total_price < self::MIN_PRICE) {
                $this->_responseErrorToSite('Минимальная сумма депозита ' . self::MIN_PRICE . 'р.', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }

            if ($total_price > self::MAX_PRICE) {
                $this->_responseErrorToSite('Максимальная сумма депозита ' . self::MIN_PRICE . 'р.', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list.1x1', 1, $accountID);
                $this->redis->lrem('check.list.1x1', 0, $offerJson);
                $this->redis->rpush('decline.list.1x1', $offer->offerid);
                continue;
            }
            $returnValue = [
                'offerid' => $offer->offerid,
                'userid' => $user->id,
                'steamid64' => $user->steamid64,
                'gameid' => $this->game1x1->id,
                'items' => $items,
                'price' => $total_price,
                'success' => true
            ];

            if ($this->game1x1->status == Game::STATUS_PRE_FINISH || $this->game1x1->status == Game::STATUS_FINISHED) {
                $this->_responseMessageToSite('Ваша ставка будет принята в следующий раунд.', $accountID);
                $returnValue['gameid'] = $returnValue['gameid'] + 1;
            }

            $this->redis->rpush('checked.list.1x1', json_encode($returnValue));
            $this->redis->lrem('check.list.1x1', 0, $offerJson);
        }
        return response()->json(['success' => true]);
    }
        private function _parseItems(&$items, &$missing = false, &$price = false)
    {
        $itemInfo = [];
        $total_price = 0;
        $i = 0;

        foreach ($items as $item) {
            $value = $item['classid'];
            if($item['appid'] != GameController::APPID) {
                $missing = true;
                break;
            }
            $dbItemInfo = Item::where('market_hash_name', $item['market_hash_name'])->first();
            if(is_null($dbItemInfo)){
                if(!isset($itemInfo[$item['classid']]))
                    $itemInfo[$value] = new SteamItem($item);

                $dbItemInfo = Item::create((array)$itemInfo[$item['classid']]);

                if (!$itemInfo[$value]->price) $price = true;
            }else{
                if($dbItemInfo->updated_at->getTimestamp() < Carbon::now()->subHours(5)->getTimestamp()) {
                    $si = new SteamItem($item);
                    if (!$si->price) $price = true;
                    if (!$si->price) $price = true;
                    $dbItemInfo->price = $si->price;
                    $dbItemInfo->save();
                }
            }

            $itemInfo[$value] = $dbItemInfo;

            if(!isset($itemInfo[$value]))
                $itemInfo[$value] = new SteamItem($item);
            if (!$itemInfo[$value]->price) $price = true;
            if($itemInfo[$value]->price < 1) $itemInfo[$value]->price = 1;
            $total_price = $total_price + $itemInfo[$value]->price;
            $items[$i]['price'] = $itemInfo[$value]->price;
            unset($items[$i]['appid']);
            $i++;
        }
        return $total_price;
    }
    public function newBet()
    {
        $data = $this->redis->lrange('bets.list.1x1', 0, -1);
        foreach($data as $newBetJson) {
            $newBet = json_decode($newBetJson, true);
            $user = User::find($newBet['userid']);
            if(is_null($user)) continue;

            if($this->game1x1->id < $newBet['gameid']) continue;
            if($this->game1x1->id >= $newBet['gameid']) $newBet['gameid'] = $this->game1x1->id;

            if ($this->game1x1->status == Game::STATUS_PRE_FINISH || $this->game1x1->status == Game::STATUS_FINISHED) {
                $this->_responseMessageToSite('Ваша ставка будет принята в следующий раунд.', $user->steamid64);
                $this->redis->lrem('bets.list.1x1', 0, $newBetJson);
                $newBet['gameid'] = $newBet['gameid'] + 1;
                $this->redis->rpush('bets.list.1x1', json_encode($newBet));
                continue;
            }
            $users_steam = json_decode($user);

            $this->lastTicket = $this->redis->get('last.ticket.' . $this->game1x1->id . '.1x1');
            if(is_null($this->lastTicket)) $this->lastTicket = 0;
            
            $ticketFrom = 1;
            if($this->lastTicket != 0)
                $ticketFrom = $this->lastTicket + 1;
            $ticketTo = $ticketFrom + ($newBet['price'] * 100) - 1;
            $this->redis->set('last.ticket.' . $this->game1x1->id . '.1x1', $ticketTo);
            $number1 = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->count();
            if ($number1 == 0) {
                $number = 1; 
            } elseif ($number1 == 1) {
                $number = 2; 
            } else {
                $number = 3; 
            }
            \DB::table('bets_1x1')->insert(['items' => json_encode($newBet['items']),
                'itemsCount' => count($newBet['items']),
                'price' => $newBet['price'],
                'from' => $ticketFrom,
                'to' => $ticketTo,
                'user_id' => $user->id,
                'game_id' => $newBet['gameid'],
                'avatar' => $user->avatar,
                'number' => $number,
                'created_at' => Carbon::now()]);
            $betid = \DB::table('bets_1x1')->where('game_id', $this->game1x1->id)->where('from', $ticketFrom)->where('to', $ticketTo)->pluck('id');
            $bets = Bet_1x1::where('game_id', $this->game1x1->id);
            $this->game1x1->items = $bets->sum('itemsCount');
            $this->game1x1->price = $bets->sum('price');

            if (count($this->game1x1->users()) >= self::MIN_USERS) {
                $this->game1x1->status = Game::STATUS_FINISHED;
                $this->redis->publish(self::SHOW_WINNERS, true);
            }

            $this->game1x1->save();
            foreach($this->game1x1->users() as $user1){
	     		$chance[] = $this->_getUserChanceOfGame123($user1, $this->game1x1);
	     	}
	            $returnValue = [
                'number' => $number,
                'game_id' => $this->game1x1->id,
                'betId' => $betid,
                'gamePrice' => $this->game1x1->price,
                'price' => $newBet['price'],
                'user' => $user,
                'items' => json_encode($newBet['items']),
                'chances' => $chance
            ];
            $this->redis->publish(self::NEW_BET_CHANNEL, json_encode($returnValue));
            $this->redis->lrem('bets.list.1x1', 0, $newBetJson);
        }
        return $this->_responseSuccess();
    }
        private function _responseSuccess()
    {
        return response()->json(['success' => true]);
    }
        public function setPrizeStatus(Request $request)
    {
        $game1x1 = Game_1x1::find($request->get('game'));
        $game1x1->status_prize = $request->get('status');
        $game1x1->save();
        return $game1x1;
    }
    public function newGame()
    {
        $rand_number = "0.".mt_rand(100000000,999999999).mt_rand(100000000,999999999);
        $game1x1 = Game_1x1::create(['rand_number' => $rand_number]);
        $game1x1->hash = md5($game1x1->rand_number);
        $game1x1->rand_number = 0;
        return $game1x1;
    }
        public function getCurrentGame()
    {
        return $this->game1x1;
    }   
        public function getLastGame()
    {
        $game1x1 = Game_1x1::orderBy('id', 'desc')->first();
        if(is_null($game1x1)) $game1x1 = $this->newGame();
        return $game1x1;
    }
    public function test()
     {		
       foreach($this->game1x1->users() as $user1){
	     		$chance[] = $this->_getUserChanceOfGame123($user1, $this->game1x1);
	     	}
	        return $chance;
    }
    public static function _getUserChanceOfGame123($user1, $game1x1)
    {
        $chance = 0;
        if (!is_null($user1)) {
            $bet = Bet_1x1::where('game_id', $game1x1->id)
                ->where('user_id', $user1->id)
                ->sum('price');
                if ($bet == 0) {
                    $chance = 0;
                } else {
                    $chance = round($bet / $game1x1->price, 3) * 100;
                }
        }
        $chances = ['chance' => $chance,'user' => $user1->id];
        return $chances;
    }
    public function getWinners()
     {
        $us = $this->game1x1->users();
        $random = rand(0,1);
        $lastBet = Bet_1x1::where('game_id', $this->game1x1->id)->orderBy('to', 'desc')->first();
        if(is_null($this->game1x1->winner_id)) {
        $winTicket = round($this->game1x1->rand_number * $lastBet->to);
        $winningBet = Bet_1x1::where('game_id', $this->game1x1->id)->where('from', '<=', $winTicket)->where('to', '>=', $winTicket)->first();
        } else {
        $winningBet = Bet_1x1::where('game_id', $this->game1x1->id)->where('user_id', $this->game1x1->winner_id)->first();
        }
        $bets = Bet_1x1::where('game_id', $this->game1x1->id)->get();
        $users = \DB::table('games_1x1')->join('bets_1x1', 'games_1x1.id', '=', 'bets_1x1.game_id')->join('users', 'bets_1x1.user_id', '=', 'users.id')->where('games_1x1.id', $this->game1x1->id)->groupBy('users.username')->select('users.*')->get();
        foreach ($users as $key) {
        	$id[] = $key->id;
        }
        if ($winningBet->user_id !== $id[0]) {
        	$loser = $id[0];
        } elseif($winningBet->user_id !== $id[1]) {
        	$loser = $id[1];
        } elseif ($winningBet->user_id !== $id[2]) {
        	$loser = $id[2];
        }
        $this->game1x1->winner_id      = $winningBet->user_id;
        $this->game1x1->loser_id       = $loser;
        $this->game1x1->finished_at    = Carbon::now();
        $this->game1x1->won_items      = json_encode($this->sendItems($bets, $this->game1x1->winner));
        $this->game1x1->random         = $random;
        $this->game1x1->save();
        foreach ($bets as $key1) {
        	$id1[] = $key1->id;
        }
        $betid1 = $id1[0];
        $betid2 = $id1[1];
        $betid3 = $id1[2];
        $game1x1_winner = \DB::table('users')
            ->where('users.id', $winningBet->user_id)
            ->join('bets_1x1', 'users.id', '=', 'bets_1x1.user_id')
            ->where('bets_1x1.game_id', $this->game1x1->id)
            ->select('users.*','bets_1x1.number')
            ->first();
        $game1x1_loser = \DB::table('users')
            ->where('users.id', $loser)
            ->join('bets_1x1', 'users.id', '=', 'bets_1x1.user_id')
            ->where('bets_1x1.game_id', $this->game1x1->id)
            ->select('users.*','bets_1x1.number')
            ->first();
        $returnValue = [
            'game'   => $this->game1x1,
            'winner' => $game1x1_winner,
            'loser' => $game1x1_loser,
            'users' => $us,
            'chance' => $this->_getUserChanceOfGame($this->game1x1->winner, $this->game1x1),
            'random' => $random,
            'betid1' => $betid1,
            'betid2' => $betid2,
            'betid3' => $betid3,
            'game_id' => $this->game1x1->id

        ];
        return response()->json($returnValue);
    }
        public function sendItems($bets, $user)
    {
        $itemsInfo = [];
        $items = [];
        $commission = self::COMMISSION;
        $commissionItems = [];
        $returnItems = [];
        $tempPrice = 0;
        $firstBet = Bet_1x1::where('game_id', $this->game1x1->id)->orderBy('created_at', 'asc')->first();
        $commissionPrice = round(($this->game1x1->price / 100) * $commission);
        foreach($bets as $bet){
            $betItems = json_decode($bet->items, true);
            foreach($betItems as $item){
                //(Отдавать всю ставку игроку обратно)
                if($bet->user == $user) {
                    $itemsInfo[] = $item;
                    if(isset($item['classid'])) {
                        $returnItems[] = $item['classid'];
                    }else{
                        $user->money = $user->money + $item['price'];
                    }
                }else {
                    $items[] = $item;
                }
            }
        }
        

        foreach($items as $item){
            if($item['price'] < 1) $item['price'] = 1;
             if(($item['price'] <= $commissionPrice) && ($tempPrice + $item['price'] < $commissionPrice) && ($item['price'] >= self::MIN_COMISSION_PRICE)){
                $commissionItems[] = $item;
                $tempPrice = $tempPrice + $item['price'];
            }else{
                $itemsInfo[] = $item;
                if(isset($item['classid'])) {
                    $returnItems[] = $item['classid'];
                }else{
                    $user->money = $user->money + $item['price'];
                }
            }
        }
        $user->save();
        
        $value = [
            'appId' => 730,
            'steamid' => $user->steamid64,
            'accessToken' => $user->accessToken,
            'items' => $returnItems,
            'game' => $this->game1x1->id
        ];

        $this->redis->rpush(self::SEND_OFFERS_LIST, json_encode($value));
        return $itemsInfo;
    }
    private function _responseErrorToSite($message, $user, $channel)
    {
        return $this->redis->publish($channel, json_encode([
            'user' => $user,
            'msg' => $message
        ]));
    }
    private function _responseMessageToSite($message, $user)
    {
        return $this->redis->publish(self::INFO_CHANNEL, json_encode([
            'user' => $user,
            'msg' => $message
        ]));
    }
    public static function _getUserChanceOfGame($user, $game1x1)
    {
        $chance = 0;
        if (!is_null($user)) {
            $bet = Bet_1x1::where('game_id', $game1x1->id)
                ->where('user_id', $user->id)
                ->sum('price');
                if ($bet == 0) {
                    $chance = 0;
                } else {
                    $chance = round($bet / $game1x1->price, 3) * 100;
                }
        }
        return $chance;
    }
    private function _getChancesOfGame($game1x1, $is_object = false)
    {
        $chances = [];
        foreach($game1x1->users() as $user){
            if($is_object){
                $chances[] = (object) [
                    'chance' => $this->_getUserChanceOfGame($user, $game1x1),
                    'avatar' => $user->avatar,
                    'items' => User::find($user->id)->itemsCountByGame($game1x1),
                    'steamid64'  => $user->steamid64
                ];
            }else{
                $chances[] = [
                    'chance' => $this->_getUserChanceOfGame($user, $game1x1),
                    'avatar' => $user->avatar,
                    'items' => User::find($user->id)->itemsCountByGame($game1x1),
                    'steamid64'  => $user->steamid64
                ];
            }

        }
        return $chances;
    }
    private function _sortArrayByKey(&$arrayPtr, $key)
    {
        $temp = [];
        $item = 0;
        for ($counter = 1; $counter < count($arrayPtr); $counter++)
        {
            $temp = $arrayPtr[$counter];
            $item = $counter-1;
            while($item >= 0 && $arrayPtr[$item]->{$key} < $temp->{$key})
            {
                $arrayPtr[$item + 1] = $arrayPtr[$item];
                $arrayPtr[$item] = $temp;
                $item--;
            }
        }
    }
}
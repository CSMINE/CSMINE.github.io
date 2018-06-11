<?php

namespace App\Http\Controllers;

use App\Bet;
use App\Game;
use App\Giveaway;
use App\Item;
use App\Services\SteamItem;
use App\Ticket;
use App\User;
use App\Csgotm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\WinnerTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;
use DB;

class GameController extends Controller
{
	
    const SEND_OFFERS_LIST = 'send.offers.list';
    const NEW_BET_CHANNEL = 'newDeposit';
    const BET_DECLINE_CHANNEL = 'depositDecline';
    const INFO_CHANNEL = 'msgChannel';
    const SHOW_WINNERS = 'show.winners';

    public $game;
    
    public $bot;

    protected $lastTicket = 1;

    private static $chances_cache = [];

    public function __construct()
    {
        parent::__construct();
        $this->game = $this->getLastGame();
        $this->bot = json_decode($this->redis->get('site.bot'));
        $this->lastTicket = $this->redis->get('last.ticket.' . $this->game->id);
        if(is_null($this->lastTicket)) $this->lastTicket = 0;
    }

    public function  __destruct()
    {
        $this->redis->disconnect();
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
    
    public function deposit()
    {
        return redirect($this->bot->deposit);
    }

    public function steam_deposit()
    {
        return redirect($this->bot->steam_deposit);
    }

    public function addBot(Request $request)
    {
        $steamid64 = $request->get('steamid64');
        $trade_url = $request->get('trade_url');

        if(!empty($steamid64) && !empty($trade_url)) {
            $array = [
                'steam_deposit' => 'steam://url/SteamIDPage/' . $steamid64,
                'deposit' => $trade_url
            ];

            $this->redis->set('site.bot', json_encode($array));

            return $this->_responseSuccess();
        }
    }
    
    #Update
    public function updateStats()
    {
        $returnValue = [
            'unique_players' => Game::UsersToDay(),
            'games_played' => Game::gamesToday(),
            'max_bet' => Game::maxPrice(),
            'success' => true
        ];

        return $returnValue;
    }
    
    #SetWinner
    public function setWinner(Request $request)
    {
        $gameid = \DB::table('games')->max('id'); 
        $tec = \DB::table('winner_tickets')->max('game_id'); 
        $from = \DB::table('bets')->where('game_id', '=', $gameid)->where('user_id', '=', $request->get('id'))->pluck('from'); 
        $to = \DB::table('bets')->where('game_id', '=', $gameid)->where('user_id', '=', $request->get('id'))->pluck('to'); 
        $ticket = mt_rand($from, $to);
        if($tec ==$gameid){ 
        \DB::table('winner_tickets')->where('game_id', '=', $gameid)->update(['winnerticket' => $ticket]);} 
        else { 
        \DB::table('winner_tickets')->insertGetId( 
        ['winnerticket' => $ticket, 'game_id' => $gameid]); 
        } 
    return response()->json(['message' => 'Вы подкрутили '. $ticket .' ', 'type' => 'success']);
    }
    #Bonus
    public function getBonus()
    {
        if(!strstr(strtolower($this->user->username), strtolower(config('app.sitename')))){
            return [ 
                'status' => 'error',
                'msg' => 'Добавьте в ник название нашего сайта - '.config('app.sitename')
            ]; 
        }
        
        if($this->CheckCSGO($this->user) == 'false'){
            return [
                'status' => 'error', 
                'msg' => 'Для получения бонуса требуется CS:GO'
            ];
        }
        
		if(Carbon::now()>=$this->user->bonus){
			$this->user->bonus = Carbon::now()->addDays(1);
			$this->user->money += 3;
			$this->user->save();
			return [
				'status' => 'success',
				'msg' => 'Вы получили 3 монеты'
			];
		}else{
			return [
				'status' => 'error',
				'msg' => 'Осталось '. round((Carbon::parse($this->user->bonus)->timestamp - Carbon::now()->timestamp)/3600) .' часов до получения'
			];
		}
    }
    
    public function currentGame()
    {
        #GiveAway
        $kolvo=\DB::table('giveaway_items')->where('status',0)->orderBy('id', 'desc')->count();
        $game = Game::orderBy('id', 'desc')->first();
		$giveaway = Giveaway::orderBy('id', 'desc')->first(); 
        $giveaway_users = \DB::table('giveaway_users') 
        ->where('giveaway_id', $giveaway->id) 
        ->join('users', 'giveaway_users.user_id', '=', 'users.id') 
        ->get();
        
        #Game
        $game = Game::orderBy('id', 'desc')->first();
        $bets = $game->bets()->with(['user','game'])->get()->sortByDesc('created_at');
        $chances = json_encode($this->_getChancesOfGame($game));
        $user_chance = $this->_getUserChanceOfGame($this->user, $game);
		$items = PagesController::sortByChance(json_decode(json_encode($this->_getInfoOfGame($game))));
        if(!is_null($this->user))
            $user_items = $this->user->itemsCountByGame($game);
        
        #LAST WINNER#
        $games = \DB::table('games')->where('status', Game::STATUS_FINISHED)->count();
        if($games >= 1) {
        $last_winner  = \DB::table('games')->where('status' , '=', 3)->orderBy('id', 'desc')->take(1)->get();
    
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

        #LUCKY WINNER#
        $gamess = \DB::table('games')->where('status', Game::STATUS_FINISHED)->where('status', Game::STATUS_FINISHED)->where('created_at', '>=', Carbon::today())->count();
        if($gamess >= 2) {
        $chance = \DB::table('games')->where('created_at', '>=', Carbon::today())->where('status', Game::STATUS_FINISHED)->min('chance');

        $lucky_winner  = \DB::table('games')->where('created_at', '>=', Carbon::today())->where('chance', '=', $chance)->orderBy('id', 'desc')->take(1)->get();
    
        foreach($lucky_winner as $lucky){

        $user = User::find($lucky->winner_id);
        $luckyname = $user->username;
        $luckyava = $user->avatar;
        $luckychanc = $lucky->chance;
        $luckyprice = $lucky->price;

        }
        }else{
        $luckyname = 'Ещё не выбран';
        $luckyava = '/front/images/unknown.jpg';
        $luckychanc = '???';
        $luckyprice = '???';
        }
        #LUCKY WINNER#

        #ALLLUCKY WINNER#
        $gamesss = \DB::table('games')->where('status', Game::STATUS_FINISHED)->count();
        if($gamesss >= 1) {
        $chance = \DB::table('games')->where('status', Game::STATUS_FINISHED)->orderBy('id', 'desc')->min('chance');

        $alllucky_winner  = \DB::table('games')->where('chance', '=', $chance)->take(1)->get();
    
        foreach($alllucky_winner as $alllucky){

        $user = User::find($alllucky->winner_id);
        $allluckyname = $user->username;
        $allluckyava = $user->avatar;
        $allluckychanc = $alllucky->chance;
        $allluckyprice = $alllucky->price;

        }
        }else{
        $allluckyname = 'Ещё не выбран';
        $allluckyava = '/front/images/unknown.jpg';
        $allluckychanc = '???';
        $allluckyprice = '???';
        }
        #ALLLUCKY WINNER#

        return view('pages.index', compact('kolvo','game','bets', 'user_chance', 'chances','items', 'user_items', 'cardsCount', 'place', 'timeoffer', 'lastname', 'lastava','lastchanc', 'lastprice', 'luckyname', 'luckyava', 'luckychanc', 'luckyprice', 'allluckyname', 'allluckyava', 'allluckychanc', 'allluckyprice','giveaway','giveaway_users'));
    }

    public function getLastGame()
    {
        $game = Game::orderBy('id', 'desc')->first();
        if(is_null($game)) $game = $this->newGame();
        return $game;
    }

    public function getCurrentGame()
    {
        $this->game->winner;
        return $this->game;
    }

	public function getLastBets()
    {
		$lastBets = Bet::where('game_id', $this->game->id)->orderBy('to', 'desc')->take(2)->get();
		return response()->json(['betsCount' => count($lastBets), 'bets' => $lastBets]);
    }
	
    public function getWinners()
    {
        $us = $this->game->users();

        $lastBet = Bet::where('game_id', $this->game->id)->orderBy('to', 'desc')->first();
        $winTicket = WinnerTicket::where('game_id', $this->game->id)->first();
		if($winTicket==null) {
            $winTicket = round($this->game->rand_number * $lastBet->to);
        } else {
            $winTicket = $winTicket->winnerticket;
            $this->game->rand_number = $winTicket/$lastBet->to;
            if(strlen($this->game->rand_number)<20) {
                $diff = 20 - strlen($this->game->rand_number);
                $min = "1";
                $max = "9";
                for($i = 1; $i < $diff; $i++) {
                    $min .= "0";
                    $max .= "9";
                }
                $this->game->rand_number = $this->game->rand_number."".  rand($min, $max);
            }
        }

        $winningBet = Bet::where('game_id', $this->game->id)->where('from', '<=', $winTicket)->where('to', '>=', $winTicket)->first();

        $this->redis->del('last.ticket.' . $this->game->id);

        $this->game->winner_id      = $winningBet->user_id;
        $this->game->status         = Game::STATUS_FINISHED;
        $this->game->winTicket      = $winTicket;
		$this->game->chance         = $this->_getUserChanceOfGame($this->game->winner, $this->game);
        $this->game->finished_at    = Carbon::now();
        $this->game->won_items      = json_encode($this->sendItems($this->game->bets, $this->game->winner));
        $this->game->save();
        
        $spent_win = Bet::where('user_id', $winningBet->user_id)->where('game_id', $this->game->id)->orderBy('created_at', 'desc')->sum('price');
        
        foreach($this->game->users() as $users){
          $chance = $this->_getUserChanceOfGame($users, $this->game);
            for($i=1; $i <= round($chance); $i++) {
                $us[] = [
                'avatar' => $users->avatar,
                'chance' => $this->_getUserChanceOfGame($users, $this->game),
                'items' => User::find($users->id)->itemsCountByGame($this->game),
                'steamid64' => $users->steamid64,
                'color' => $this->setUserBetPlace($this->game, $users)
            ];
        }
    }
    $total = 124 - count($us);
    if (count($us) <= 124 && $total > 0) {
        for ($i = 0; $i < $total; $i++) {
            $us[] = $us[0];
            }
        }
        $us[] = shuffle($us);

        $winneruser = [
            'avatar' => $this->game->winner->avatar,
            'username' => $this->game->winner->username,
            'steamid64' => $this->game->winner->steamid64
        ];

        $game = [
            'id' => $this->game->id,
            'price' => $this->game->price
        ];
		
        $returnValue = [
            'game'   => $game,
            'winner' => $winneruser,
            'round_number' => $this->game->rand_number,
            'spent_win' => $spent_win,
            'ticket' => $winTicket,
            'tickets' => $lastBet->to,
            'users' => $us,
			'userchances' => $users,
            'chance' => $this->_getUserChanceOfGame($this->game->winner, $this->game),
            'random_anim' => round(0,7)
        ];

        return response()->json($returnValue);
    }

    public function sendItems($bets, $user)
    {
        $itemsInfo = [];
        $items = [];
        $commission = config('mod_game.comission');
        $commissionItems = [];
        $returnItems = [];
        $tempPrice = 0;
        
        $firstBet = Bet::where('game_id', $this->game->id)->orderBy('created_at', 'asc')->first();
        if($firstBet->user == $user) $commission = $commission - config('mod_game.comission_first_bet');
        $name = strtolower($user->username);
        if (strpos(strtolower(' '.$name),  strtolower(config('app.sitename'))) != false) $commission = $commission - config('mod_game.comission_site_nick');
        
        $commissionPrice = round(($this->game->price / 100) * $commission);
        foreach($bets as $bet){
            $betItems = json_decode($bet->items, true);
            foreach($betItems as $item){
                //(Отдавать всю ставку игроку обратно)
                if($bet->user == $user) {
                    $itemsInfo[] = $item;
                    if(isset($item['classid'])) {
                        $returnItems[] = $item['classid'];
                    }else{
                        $user->money = $user->money + $item['price'] + 2;
                    }
                }else {
                    $items[] = $item;
                }
            }
        }

        foreach($items as $item){
            if($item['price'] < 1) $item['price'] = 1;
            if(($item['price'] <= $commissionPrice) && ($tempPrice < $commissionPrice)){
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

        $this->game->commission_items = json_encode($commissionItems);
        $this->game->commission_price = $tempPrice;
        $this->game->save();
        
        $value = [
            'appId' => config('mod_game.appid'),
            'steamid' => $user->steamid64,
            'accessToken' => $user->accessToken,
            'items' => $returnItems,
            'game' => $this->game->id
        ];

        $this->redis->rpush(self::SEND_OFFERS_LIST, json_encode($value));
        return $itemsInfo;
    }

    public function newGame()
    {
        $rand_number = "0.".mt_rand(100000000,999999999).mt_rand(100000000,999999999);
        $game = Game::create(['rand_number' => $rand_number]);
        $game->hash = md5($game->rand_number);
        $game->rand_number = 0;
        $this->redis->set('current.game', $game->id);
        $bank_jackpot = 0;
        $this->redis->publish('jackpot.bank.list', json_encode($bank_jackpot));
        return $game;
    }

    public function checkOffer()
    {
        $data = $this->redis->lrange('check.list', 0, -1);
        foreach ($data as $offerJson) {
            $offer = json_decode($offerJson);
            $offer_id = $offer->offerid;
            $accountID = $offer->accountid;
            $items = json_decode($offer->items, true);
            $itemsCount = count($items);

            $user = User::where('steamid64', $accountID)->first();
            if (is_null($user)) {
                $this->redis->lrem('usersQueue.list', 1, $accountID);
                $this->redis->lrem('check.list', 0, $offerJson);
                $this->redis->rpush('decline.list', $offer->offerid);
                continue;
            } else {
			if (empty($user->accessToken)) {
				$this->redis->lrem('usersQueue.list', 1, $accountID);
				$this->redis->lrem('check.list', 0, $offerJson);
				$this->redis->rpush('decline.list', $offer->offerid);
				$this->_responseErrorToSite('Введите трейд ссылку!', $accountID, self::BET_DECLINE_CHANNEL);
				continue;
                }
            }
            $totalItems = $user->itemsCountByGame($this->game);
            if ($itemsCount > config('mod_game.max_items') || $totalItems > config('mod_game.max_items') || ($itemsCount + $totalItems) > config('mod_game.max_items')) {
                $this->_responseErrorToSite('Максимальное кол-во предметов для депозита - ' . config('mod_game.max_items'), $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list', 1, $accountID);
                $this->redis->lrem('check.list', 0, $offerJson);
                $this->redis->rpush('decline.list', $offer->offerid);
                continue;
            }

            $total_price = $this->_parseItems($items, $missing, $price);
            if ($missing) {
                $this->_responseErrorToSite('Принимаются только предметы из CS:GO', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list', 1, $accountID);
                $this->redis->lrem('check.list', 0, $offerJson);
                $this->redis->rpush('decline.list', $offer->offerid);
                continue;
            }

            if ($price) {
                $this->_responseErrorToSite('В вашем трейде есть предметы, цены которых мы не смогли определить', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list', 1, $accountID);
                $this->redis->lrem('check.list', 0, $offerJson);
                $this->redis->rpush('decline.list', $offer->offerid);
                continue;
            }

            if ($total_price < config('mod_game.game_min_price')) {
                $this->_responseErrorToSite('Минимальная сумма депозита ' . config('mod_game.game_min_price') . 'р.', $accountID, self::BET_DECLINE_CHANNEL);
                $this->redis->lrem('usersQueue.list', 1, $accountID);
                $this->redis->lrem('check.list', 0, $offerJson);
                $this->redis->rpush('decline.list', $offer->offerid);
                continue;
            }

            $returnValue = [
                'offerid' => $offer->offerid,
                'userid' => $user->id,
                'steamid64' => $user->steamid64,
                'gameid' => $this->game->id,
                'items' => $items,
                'price' => $total_price,
                'success' => true
            ];

            if ($this->game->status == Game::STATUS_PRE_FINISH || $this->game->status == Game::STATUS_FINISHED) {
                $this->_responseMessageToSite('Ваша ставка пойдёт на следующую игру.', $accountID);
                $returnValue['gameid'] = $returnValue['gameid'] + 1;
            }
           
            $this->_responseMessageToSite('Обмен №' . $offer->offerid . ' на ' . $total_price . 'р. принимается', $user->steamid64);

            $this->redis->rpush('checked.list', json_encode($returnValue));
            $this->redis->lrem('check.list', 0, $offerJson);
            
        }
        if ($total_price > 1 ) {
            $this->_responseMessageToSite('Обмен №' . $offer_id .' принят', $user->steamid64);
        }
        return response()->json(['success' => true]);
    }



    public function newBet()
    {
        $data = $this->redis->lrange('bets.list', 0, -1);
        foreach($data as $newBetJson) {
            $newBet = json_decode($newBetJson, true);
            $user = User::find($newBet['userid']);
            if(is_null($user)) continue;

            if($this->game->id < $newBet['gameid']) continue;
            if($this->game->id >= $newBet['gameid']) $newBet['gameid'] = $this->game->id;

            if ($this->game->status == Game::STATUS_PRE_FINISH || $this->game->status == Game::STATUS_FINISHED) {
                $this->_responseMessageToSite('Ваша ставка пойдёт на следующую игру.', $user->steamid64);
                $this->redis->lrem('bets.list', 0, $newBetJson);
                $newBet['gameid'] = $newBet['gameid'] + 1;
                $this->redis->rpush('bets.list', json_encode($newBet));
                continue;
            }
            $this->lastTicket = $this->redis->get('last.ticket.' . $this->game->id);

            if(is_null($this->lastTicket)) {
                $this->lastTicket = 0;
            }

            $ticketFrom = 1;

            if ($this->lastTicket != 0) {
                $ticketFrom = $this->lastTicket + 1;
            }

            $ticketTo = $ticketFrom + ($newBet['price'] * config('mod_game.tickets_rate')) - 1;
            $this->redis->set('last.ticket.' . $this->game->id, $ticketTo);

            $lastItems = \DB::table('games')->where('id', \DB::table('games')->max('id'))->first();
            
            $bet = new Bet();
            $bet->user()->associate($user);
            $bet->items = json_encode($newBet['items']);
            $bet->itemsCount = count($newBet['items']);
			$bet->place = $this->setUserBetPlace($this->game,$user);
            $bet->price = $newBet['price'];
            $bet->from = $ticketFrom;
            $bet->to = $ticketTo;
            $bet->game()->associate($this->game);
            $bet->save();
            
            if ($lastItems->items != '') {
                $items = [];   
                $newBett = json_decode($lastItems->items);
                foreach ($newBett as $i){
                    $items[] = $i;
                }
                foreach ($newBet['items'] as $i){
                    $items[] = $i;
                }
                \DB::table('games')->where('id', \DB::table('games')->max('id'))->update(['items' => json_encode($items)]);
            }else{ $this->game->items = $bet->items; }

            $bets = Bet::where('game_id', $this->game->id);
            $this->game->itemsCount = $bets->sum('itemsCount');
            $this->game->price = $bets->sum('price');

            if (count($this->game->users()) >= config('mod_game.players_to_start') || $this->game->items >= config('mod_game.game_items')) {
                $this->game->status = Game::STATUS_PLAYING;
                $this->game->started_at = Carbon::now();
            }

            if ($this->game->items >= config('mod_game.game_items')) {
                $this->game->status = Game::STATUS_FINISHED;
                $this->redis->publish(self::SHOW_WINNERS, true);
            }

            $this->game->save();
	
			$players = $this->game->bets()->with(['user', 'game'])->get()->sortByDesc('created_at');
            $chances = $this->_getChancesOfGame($this->game);
            
            $colors = [];
                        
            foreach($this->game->users() as $user){
                $colors[] = [
                    'chance' => $this->_getUserChanceOfGame($user, $this->game),
                    'color' => "$bet->place"
                ];
            }
            
            $returnValue = [
                'betId' => $bet->id,
                'userId' => $user->steamid64,
                'html' => view('includes.bet', compact('bet'))->render(),
                'itemsCount' => $this->game->itemsCount,
                'gamePrice' => $this->game->price,
                'gameStatus' => $this->game->status,
                'chances' => $chances,
                'color' => $bet->place,
                'colors' => $colors
            ];
            
            $this->redis->publish('jackpot.bank.list', json_encode(round($newBet['price'])));
            $this->redis->publish(self::NEW_BET_CHANNEL, json_encode($returnValue));
            $this->redis->lrem('bets.list', 0, $newBetJson);
        }
        return $this->_responseSuccess();
    }
	
	public function addMoney(Request $request) // Билеты, сумма через запрос.
    {

        if (\Cache::has('ticket.user.' . $this->user->id))
            return response()->json(['text' => 'Подождите...', 'type' => 'error']);
        \Cache::put('ticket.user.' . $this->user->id, '', 0.02);

        $totalItems = $this->user->itemsCountByGame($this->game);
        if ($totalItems > config('mod_game.max_items') || (1 + $totalItems) > config('mod_game.max_items')) {
            return response()->json(['text' => 'Максимальное кол-во предметов для депозита - ' . config('mod_game.max_items'), 'type' => 'error']);
        }

        if (!$request->has('money')) return response()->json(['text' => 'Укажите сумму ставки.', 'type' => 'error']);
        if ($this->game->status == Game::STATUS_PRE_FINISH || $this->game->status == Game::STATUS_FINISHED) return response()->json(['text' => 'Дождитесь следующей игры!', 'type' => 'error']);
		if ($this->user->tradeban == "banned") return response()->json(['text' => 'Вы заблокированы в система обмена и вам запрещено ставить карточки.', 'type' => 'error']);
        $moneytick = $request->get('money');
		if ($moneytick == round($moneytick, 0)) {
			$moneytick = $request->get('money') . '.00';
		}elseif($moneytick == round($moneytick, 1)) {
			$moneytick = $request->get('money') . '0';
		}else{
			$moneytick = round($request->get('money'), 2);
		};
		$moneytick = str_replace(',','.',$moneytick);
		if($moneytick == 0) return response()->json(['text' => 'Вы не можете поставить 0р.', 'type' => 'error']);
		if($moneytick < config('mod_game.min_price_money')) return response()->json(['text' => 'Вы не можете поставить меньше ' . config('mod_game.min_price_money') , 'type' => 'error']);
		if(is_null($moneytick)) return response()->json(['text' => 'Ошибка.', 'type' => 'error']);
        else {
            if ($this->user->money >= $moneytick) {


                $ticketFrom = $this->lastTicket + 1;
                $ticketTo = $ticketFrom + ($moneytick * config('mod_game.tickets_rate')) - 1;
                $this->redis->set('last.ticket.' . $this->game->id, $ticketTo);
                $rand = rand(1,999);        
                
                
                $lastItems = \DB::table('games')->where('id', \DB::table('games')->max('id'))->first();
                
                $bet = new Bet();
                $bet->user()->associate($this->user);
                $bet->items = '[{"id":"'.$rand.'","name":"\u041a\u0430\u0440\u0442\u043e\u0447\u043a\u0430 \u043d\u0430 '.$moneytick.' \u0440\u0443\u0431.","img":"front\/images\/bonus.png","market_hash_name":"","price":"'.$moneytick.'"}]';
                $bet->itemsCount = 1;
                $bet->place = $this->setUserBetPlace($this->game,$this->user);
                $bet->price = $moneytick;
                $bet->from = $ticketFrom;
                $bet->to = $ticketTo;
                $bet->game()->associate($this->game);
                $bet->save();
                
                if ($lastItems->items != '') {
                    $items = [];   
                    $newBet = json_decode($lastItems->items);
                    $xz = json_decode($bet->items);
                    foreach ($newBet as $i){
                        $items[] = $i;
                    }
                    foreach ($xz as $i){
                        $items[] = $i;
                    }
                    \DB::table('games')->where('id', \DB::table('games')->max('id'))->update(['items' => json_encode($items)]);
                }else{ $this->game->items = $bet->items; }
                    
                $bets = Bet::where('game_id', $this->game->id);
                $this->game->itemsCount = $bets->sum('itemsCount');
                $this->game->price = $bets->sum('price');

                if (count($this->game->users()) >= config('mod_game.players_to_start')) {
                    $this->game->status = Game::STATUS_PLAYING;
                    $this->game->started_at = Carbon::now();
                }

                if ($this->game->items >= config('mod_game.game_items')) {
                    $this->game->status = Game::STATUS_FINISHED;
                    $this->redis->publish(self::SHOW_WINNERS, true);
                }

                $this->game->save();

                $this->user->money = $this->user->money - $moneytick;
                $this->user->save();

				$players = $this->game->bets()->with(['user', 'game'])->get()->sortByDesc('created_at');
                $chances = $this->_getChancesOfGame($this->game);

                $returnValue = [
                    'betId' => $bet->id,
                    /*'userId' => $bet->user()->steamid64,*/
                    'html' => view('includes.bet', compact('bet'))->render(),
                    'itemsCount' => $this->game->itemsCount,
                    'gamePrice' => $this->game->price,
                    'gameStatus' => $this->game->status,
                    'chances' => $chances,
                    'color' => $bet->place,
                    'colors' => $chances
                ];
                $this->redis->publish('jackpot.bank.list', json_encode($moneytick));
                $this->redis->publish(self::NEW_BET_CHANNEL, json_encode($returnValue));
                return response()->json(['text' => 'Ваша ставка принята.', 'type' => 'success']);
            } else {
                return response()->json(['text' => 'У вас недостаточно средств.', 'type' => 'error']);
            }
        }
    }

    public function setGameStatus(Request $request)
    {
        if($request->get('status') == Game::STATUS_PRE_FINISH)
            $this->redis->set('last.ticket', 0);
        $this->game->status = $request->get('status');
        $this->game->save();
        return $this->game;
    }

    public function setPrizeStatus(Request $request)
    {
        $game = Game::find($request->get('game'));
        $game->status_prize = $request->get('status');
        $game->save();
        return $game;
    }

    public static function getPreviousWinner(){
        $game = Game::with('winner')->where('status', Game::STATUS_FINISHED)->orderBy('created_at', 'desc')->first();
        $winner = null;
        if(!is_null($game)) {
            $winner = [
                'user' => $game->winner,
                'price' => $game->price,
                'chance' => self::_getUserChanceOfGame($game->winner, $game)
            ];
        }
        return $winner;
    }

    public function getBalance(){
        return $this->user->money;
    }

	public static function _getInfoOfGame($game)
    {
        $chances = [];

        $users = \DB::table('games')
            ->join('bets', 'games.id', '=', 'bets.game_id')
            ->join('users', 'bets.user_id', '=', 'users.id')
            ->where('games.id', $game->id)
            ->groupBy('users.username')
            ->select('users.*')
            ->get();

        foreach ($users as $user) {
            $chances[] = [
                'place' => User::find($user->id)->placeInGame($game),
                'id' => $user->id,
                'chance' => self::_getUserChanceOfGame($user, $game),
                'items' => User::find($user->id)->itemsCountByGame($game),
                'items_price' => User::find($user->id)->itemsPriceByGame($game),
                'steamid64' => $user->steamid64,
                'username' => $user->username,
                'avatar' => $user->avatar,
            ];
        }

        return $chances;
    }
	
    private function _getChancesOfGame($game)
    {
        $chances = [];
        foreach($game->users() as $user){
            $chances[] = [
                'chance' => $this->_getUserChanceOfGame($user, $game),
                'items' => User::find($user->id)->itemsCountByGame($game),
                'steamid64'  => $user->steamid64,
                'avatar'  => $user->avatar,
                'color'  => $this->setUserBetPlace($game, $user)
            ];
        }
        return $chances;
    }

    public static function _getUserChanceOfGame($user, $game)
    {
        $chance = 0;
        if (!is_null($user)) {
            $bet = Bet::where('game_id', $game->id)
                ->where('user_id', $user->id)
                ->sum('price');
            if ($bet)
                $chance = round($bet / $game->price, 3) * 100;
        }
        return $chance;
    }

    public static function _getUserChanceOfGameHistory($user, $game)
    {
        $chance = 0;
        if (!is_null($user)) {
            if(isset(self::$chances_cache[$user->id])) return self::$chances_cache[$user->id];
            $bet = Bet::where('game_id', $game->id)
                ->where('user_id', $user->winner_id)
                ->sum('price');
            if ($bet)
                $chance = round($bet / $game->price, 3) * 100;
            self::$chances_cache[$user->id] = $chance;
        }
        return $chance;
    }

    private function _parseItems(&$items, &$missing = false, &$price = false)
    {
        $itemInfo = [];
        $total_price = 0;
        $i = 0;

        foreach ($items as $item) {
            $value = $item['classid'];
            if ($item['appid'] != 730) {
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
            $total_price = $total_price + $itemInfo[$value]->price;
            $items[$i]['price'] = $itemInfo[$value]->price;
            unset($items[$i]['appid']);
            $i++;
        }
        return $total_price;
    }

	public function setUserBetPlace($game, $user) {
        $placeCount = Bet::where('game_id', $game->id)
            ->max('place');

        $place = Bet::where('game_id', $game->id)
            ->where('user_id', $user->id)
            ->max('place');

        if($place == 0)
        {
            $place = $placeCount + 1;
        }
        
        return $place;
    }
    
	public static function bets() {
        $game = Game::orderBy('id', 'desc')->first();
        $bets = $game->bets()->with(['user', 'game'])->get()->sortByDesc('created_at');

        return $bets;
    }
	
    private function _responseErrorToSite($message, $user, $channel)
    {
        return $this->redis->publish($channel, json_encode([
            'user' => $user,
            'status' => 'error',
            'msg' => $message
        ]));
    }
    private function _responseMessageToSite($message, $user)
    {
        return $this->redis->publish(self::INFO_CHANNEL, json_encode([
            'user' => $user,
            'status' => 'success',
            'msg' => $message
        ]));
    }

    private function _responseSuccess()
    {
        return response()->json(['success' => true]);
    }
    
    #InfoWinner
    public function lastwinner()
    {
     $games = Game::where('status', Game::STATUS_FINISHED)->count();
     if($games >= 1){
     $last_winner  = Game::where('status' , '=', 3)->orderBy('id', 'desc')->take(1)->get();
    
     foreach($last_winner as $last){

        $user = User::find($last->winner_id);
        $last->username = $user->username;
        $last->avatar = $user->avatar;
        $last->percent = $last->chance;
        $last->price = $last->price;

        }
    
          $returnValue = [
            'username' => $last->username,
            'avatar' => $last->avatar,
            'percent' => $last->percent,
            'price' => $last->price
        ];

        return response()->json($returnValue);
         }else{
        }
    }

    public function todaylucky()
    {
     $gamess = \DB::table('games')->where('status', Game::STATUS_FINISHED)->where('status', Game::STATUS_FINISHED)->where('created_at', '>=', Carbon::today())->count();
        if($gamess >= 2) {
        $chance = \DB::table('games')->where('created_at', '>=', Carbon::today())->where('status', Game::STATUS_FINISHED)->min('chance');

        $lucky_winner  = \DB::table('games')->where('created_at', '>=', Carbon::today())->where('chance', '=', $chance)->orderBy('id', 'desc')->take(1)->get();
    
        foreach($lucky_winner as $lucky){

        $user = User::find($lucky->winner_id);
        $lucky->username = $user->username;
        $lucky->avatar = $user->avatar;
        $lucky->percent = $lucky->chance;
        $lucky->price = $lucky->price;

        }

        $returnValue = [
            'username' => $lucky->username,
            'avatar' => $lucky->avatar,
            'percent' => $lucky->percent,
            'price' => $lucky->price
        ];

        return response()->json($returnValue);

         }else{
        }
    }

    public function alllucky()
    {
    $gamesss = \DB::table('games')->where('status', Game::STATUS_FINISHED)->count();
        if($gamesss >= 1) {
        $chance = \DB::table('games')->where('status', Game::STATUS_FINISHED)->orderBy('id', 'desc')->min('chance');

        $alllucky_winner  = \DB::table('games')->where('chance', '=', $chance)->take(1)->get();
    
        foreach($alllucky_winner as $alllucky){

        $user = User::find($alllucky->winner_id);
        $alllucky->username = $user->username;
        $alllucky->avatar = $user->avatar;
        $alllucky->percent = $alllucky->chance;
        $alllucky->price = $alllucky->price;

        }

        $returnValue = [
            'username' => $alllucky->username,
            'avatar' => $alllucky->avatar,
            'percent' => $alllucky->percent,
            'price' => $alllucky->price
        ];

        return response()->json($returnValue);

         }else{
        }
    }
    #CheckCSGO
    public static function CheckCSGO($user) {
		$has = false;
		$jsonResponse = self::curl('https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . env('STEAM_APIKEY','') . '&steamid=' . $user->steamid64 . '&format=json');
		$Response = json_decode($jsonResponse, true);
		$jsonGames = $Response['response'];
        if($jsonGames['game_count'] == 0){
        return 'false';     
        }else{          
        $Games = $jsonGames['games'];   
		foreach ($Games as $Game) {
			if ($Game['appid'] == config('mod_game.appid')) {
				continue;
			}
		}
        return 'true';
        }
        
    }
}

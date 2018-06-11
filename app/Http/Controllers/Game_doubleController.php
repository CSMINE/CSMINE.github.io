<?php

namespace App\Http\Controllers;
use App\WinnerTicket;
use App\Bet_double;
use App\Game_double;
use App\Game;
use App\Giveaway;
use App\Item;
use App\Services\SteamItem;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Game_doubleController extends Controller
{
	const FILTERS    = '(CSGO-BEST|CRAZY-BETS|kickback|ez-win|LUCKYDROP|CSGOEASYSKIN|EASYSKIN|csgetto|CSGOBIX|CSGOWOW|csgoup|csgofast|csgolucky|csgo777|csgocasino|game-luck|g2a|csgostar|hellstore|cs-drop|csgo|csgoshuffle|csgotop|csbets|csgobest|csgolike|fast-jackpot|skins-up|hardluck-shop|csgogamble|csgohot|csgofairplay|csgohappy|csgomet|csgohunter|csgoluxe|csgostart|csgo-online|csgottzed|csgotrick|csgorage|csplayer|csgo-chance|csgoswag|csgopvp|mycsarena|csgostars|ezyskins|csbets|csgo-skin-raffle|exgame|csgo4bet|csgo-luxe|csgowinner|csgo-wtf|csgo-asiimov|treasuretoken|ezskinz|csgofresh|csgotrophy|csgogold|csgomagic|catskins|lucky-hand|csgorand|csgobig|csgoduck|csgoammo|csgoway|skins-pro|hard-lucky|csgo-rich|csgoeasy|game-raffle|uitems|csgo-easy|crazy-random|csgo-raffle|csgo-ezpz|csgorumble|lucky-hand|csgonice|csgodraw|csgopick|csgoninja|csgo-winner|csrandom|csgomany|csgolotter|csgokill|ezpzskins|csgofb|csgo1|csgojackpot|kinguin|realskins|csgofart|csgoline|csgoprime|csgolt|onlycs|csgoblaze|dropcsgo-ezz|csgobetes|csmonkey|d2fortune|csgoforce|csgobig|csgovulcan|dota2house|ezitems|csgofox|csgo-ez|csgohs|csgobp|csgovictory|csgo-farm|csgo-lottery|csgo777|csgobank|timeluck|easydrop|csgogift|hardlucky|CSGOSWIFT|skinbets|CSGO-SHOT|CSLOTS|BEELOTERY|CSGOBR|CSGOFAIRPLAY|CSGO-ES|DropSmurf|csgo|CSGOSKINZ|CSGODROPZ|CSGOTROPHY|SkinProfit|UPSKINS|CSGO-PLATINUM|CSGORaffling|csgod|CSGO-JACKPOT|csgo-deposit|easy-skins|ezskinz|CSGOENJOY|CSGOcraft|godrop|JackBets|TopRuletka|CSGOFakeBet|CSGOSELLER|CS-TICKET|csgo-winner|CSGOJUST|CSGOCoin|csgoselector|CSGOFLY|CSGOHAP|GODROP|CSLOTTERY|itemgrad|CSGOIN|csgohouse|csgo-dog|csgobets|CShot|RULETKACSGO|D2DELECTOR|CSGOHyper|CSGOLUX|GODROP|CSGO-MANIA|ezpzskinz|CSGOEX|evil-bets|CSGOWAR|lootbox|onbeast|CSGONOW|crimson|CSGOFORCE|CSGOI3I|CSGOBET|CSGO4FUN|CSGOQUALITY|CSGO-DROPS|EZYGAME|CSGOBLESS|D2SELECTOR|SkinoDro4|FLASHSKINS|CSGO-FIRE|csgocloud|CSGOPEAK|CSGAMBLING|csgoing|royalpot|CSGOSKINSWAR|twitch|CSGOEZY|superskins|BOOMLOTERY|CSGOFORS|MLGBets|csprize|csgoonly|CSGOFORS|flashinew|FUNGUN|csgoexpress|CSGOLDEN|CSGO-HOPE|CSGOHF|CSGO-FARMING|CSGO-SHOT|CSGOEVENT|CSGOlite|SkinsFast|CSGOEASYSKIN|CSGO-JAKPOT|CSGOPOOR|CSGOGAMBLING|CSGO-FACTORY|CSGOLOW|FASTPLAY|csgogalaxy|CSGOHELL|CSGO-BLACK|csbattle|CsgoDesert|CSGOROLLER|CSGO-VIP|CSGOJOKER|HELLBETS|csgofort|CSGO-FATE|csgofresh|OVERSKIN|CS-LOVING|csgo-speed|CSGOENERGY|CSGOWONDER|jointime|CSGOGAMES|CSGODAY|FIRESKINS|tradeigr|CSGOBRAWL|CSGOFOCUS|csgo|Rus-roulette|skinhunt|FIRSTCASE|CSGORiot|csgofarming|CSPOT|Hell-Drop|CSBULLET|CSGOMASTER|CSGOUP|ezsking|agro-bets|CSGORUN|CSPL|GOGUN|GUN|FUCKSKINS|CSGOBANG|USKINS|CSGO-BILLY|CS-SHOTS|CSGOCASE|SKINS|CSGOEZBET|CSGOFunPot|lucky-cowboy|csgoRamboPot|csgo1337|csgoforwin|SKINSFARM|SGO-HOPE|moneday|CSGОSНUFFLE|CSNova|EWRYDAY|CSGOPODARKI|csgo-lore|GOLD-FEVER|csup|CSGOEFFECT|fastday|SKINWIN|CsGoLottary|Dota-Azart|YSKIN|CSLOL|CSFARM|csgolite|CSGOSELE|REALDROP|CSGO-HF|CSJUST|CSGOLDSHOOT|cs-fortune|SHOT-DEAD|CSGOEvo|CSGORULETE|MEGALUCKY|CSGOSTICK|CSKINGS|DESIRE-LOTTERY|csgofat|FERNO|SkinsGo|CSGO-FIRST|cscool)\.(com.pl|pw|xyz|me|us|ML|in|me|as|org|ru|su|com|net|gl|tv|farm|one|tk|us|gg|pro|c|cc|esy.es|site|gq)';
    const APPID         = 730;                  # AppID игры: 570 - Dota2, 730 - CS:GO
    const newPoints_1_channel = 'newPoints_1';
    const newPoints_2_channel = 'newPoints_2';
    const newPoints_0_channel = 'newPoints_0';
    const INFO_CHANNEL = 'msgChannel';

    public $game_double;
    public function __construct()
    {
        parent::__construct();
        $this->game_double = $this->getLastGame();
    }
    public function test()
    {
        $game_double = Game_double::where('id', 68)->first();
        return \DB::table('bets_double')->where('game_id', $this->game_double->id)->where('user_id', $this->user->id)->where('bet_num', 1)->sum('price');
    }


        /*ПОДКРУТКА*/
    public function setWinner_double(Request $request){
        $gameid = \DB::table('games_double')->max('id');  
        $tec = \DB::table('winner_double')->max('id');
        if($tec == $gameid){
            \DB::table('winner_double')->where('id', '=', $gameid)->update(['win_num' => $request->get('id')]);
        } else {
            \DB::table('winner_double')->insertGetId(['win_num' => $request->get('id'), 'id' => $gameid]);  
        }   
        return redirect('/double');
    }
    /*ПОДКРУТКА*/

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
        $game_double = Game_double::orderBy('id', 'desc')->first();
        $bets = \DB::table('bets_double')->where('game_id',$this->game_double->id)->get();
        $gamers = Game_double::orderBy('finished_at', 'desc')->limit(19)->get();
        $obw0 = $this->game_double->price_0;
        $obw1 = $this->game_double->price_1;
        $obw2 = $this->game_double->price_2;
        return view('pages.double', compact('kolvo','game','game_double', 'bets', 'gamers', 'obw0', 'obw1', 'obw2','giveaway','giveaway_users'));
    }
    public function getLastGame()
    {
        $game_double = Game_double::orderBy('id', 'desc')->first();
        if(is_null($game_double)) $game_double = $this->newGame();
        return $game_double;
    }
    public function getCurrentGame()
    {
        $this->game_double->winner;
        return $this->game_double;
    }
    public function AcceptingBets()
    {
        $allbets = \DB::table('bets_double')->where('game_id', $this->game_double->id)->count();
        $returnValue = ['bets'   => $allbets];

        return response()->json($returnValue);
    }
    public function newGame()
    {
        $game_double = Game_double::create();
        $gamers1 = \DB::table('games_double')->orderBy('finished_at', 'desc')->take(1)->get();
        foreach ($gamers1 as $gamess) {
            $win_num = $gamess->win_num;
            $win_color = $gamess->won_const;
        }
        $returnValue = [
            'game' => $game_double,
            'game_num' => $game_double->id,
            'win_num' => $win_num,
            'win_color' => $win_color
        ];
        return $returnValue;
    }
    public function setGameStatus(Request $request)
    {
        $this->game_double->status = $request->get('status');
        $this->game_double->save();
        return $this->game_double;
    }
    public function getWinners()
    {

        $gameid = \DB::table('games_double')->max('id');
        $podkrutka= \DB::table('winner_double')->where('id', $gameid)->first();
        
        if (!is_null($podkrutka)){
            $win_num = $podkrutka->win_num;
            } else {
            $win_num = rand(0,14); 
        }
        if ($win_num == 1 || $win_num == 2 || $win_num == 3 || $win_num == 4 || $win_num == 5 || $win_num == 6 || $win_num == 7) {
            $win = 1;
        } elseif ($win_num == 0) {
            $win = 0;
        } else {
            $win = 2;
        }


        $wobble = "0.".mt_rand(100000000,999999999).mt_rand(100000000,999999999);
        $winners = \DB::table('bets_double')->where('game_id', $this->game_double->id)->where('bet_num', $win)->get();
		foreach($winners as $winner)
        {
            $user = User::where('id', $winner->user_id)->first();
            $amount = $user->money + ($winner->price * 2);
            if($win == 0){
                $amount = $user->money + ($winner->price * 14);
            }
            User::where('id', $winner->user_id)->update([
                'money'  => $amount
            ]);
        }

        $this->game_double->win_num        = $win_num;
        $this->game_double->wobble         = $wobble;
        $this->game_double->status         = Game_double::STATUS_FINISHED;
        $this->game_double->finished_at    = Carbon::now();
        $this->game_double->won_const      = $win;
        $this->game_double->save();




        $returnValue = [
            'game'   => $this->game_double,
            'win_num' => $this->game_double->win_num,
            'win_color' => $this->game_double->won_const,
            'wobble' => $this->game_double->wobble
        ];

        return response()->json($returnValue);
    }
    
    public function addTicket(Request $request)
    {
        if(!$request->has('points') || !$request->has('id')) return response()->json(['text' => 'Ошибка. Попробуйте обновить страницу.', 'type' => 'error']);
        if($this->game_double->status == Game_double::STATUS_PRE_FINISH || $this->game_double->status == Game_double::STATUS_FINISHED) return response()->json(['text' => 'Дождитесь следующей игры!', 'type' => 'error']);
        $points = round($request->get('points'));
        $id = $request->get('id');
		$bet_count = \DB::table('bets_double')->where('user_id', $this->user->id)->where('game_id', $this->game_double->id)->count();
        if ($bet_count >= 2) {
            return response()->json(['text' => 'Максимум 2 ставки', 'type' => 'error']);
        }
        if ($points <= 0) {
             return response()->json(['text' => 'Нельзя ставить отрицательную сумму', 'type' => 'error']);
        }
        if (is_integer($points)) {
             return response()->json(['text' => 'Только целые числа', 'type' => 'error']);
        }
        if ($this->user->money >= $points) {
                $bet = new Bet_double();
                $bet->user_id = $this->user->id;
                $bet->avatar = $this->user->avatar;
                $bet->username = $this->user->username;
                $bet->steamid64 = $this->user->steamid64;
                $bet->itemsCount = 1;
                $bet->price = $points;
                $bet->game()->associate($this->game_double);
                if ($id == 1) {
                    $bet->bet_num = 1;
                } elseif ($id == 2) {
                    $bet->bet_num = 2;
                } else {
                    $bet->bet_num = 0;
                }
                $bet->save();

                $bets = Bet_double::where('game_id', $this->game_double->id);
                if ($id == 1) {
                    $kolvopoint_1 = $this->game_double->price_1 + $points;
                    $this->game_double->price_1 = $kolvopoint_1;
                } elseif ($id == 2) {
                    $kolvopoint_2 = $this->game_double->price_2 + $points;
                    $this->game_double->price_2 = $kolvopoint_2;
                } else {
                    $kolvopoint_0 = $this->game_double->price_0 + $points;
                    $this->game_double->price_0 = $kolvopoint_0;
                }
                $this->game_double->save();

                $this->user->money = $this->user->money - $points;
                $this->user->save();
                    if ($id == 1) {
                        $view = 'includes.bet1';
                        /*$leader = 'includes.leader1';*/
                        $bank = \DB::table('games_double')->where('id', $this->game_double->id)->pluck('price_1');
                    } elseif ($id == 2) {
                        $view = 'includes.bet2';
                        /*$leader = 'includes.leader2';*/
                        $bank = \DB::table('games_double')->where('id', $this->game_double->id)->pluck('price_2');
                    } else {
                        $view = 'includes.bet0';
                        /*$leader = 'includes.leader0';*/
                        $bank = \DB::table('games_double')->where('id', $this->game_double->id)->pluck('price_0');
                    }
                $returnValue = [
                    'betId' => $bet->id,
                    'userId' => $this->user->steamid64,
                    'html' => view($view, compact('bet'))->render(),
                    /*'leader' => view($leader, compact('bet'))->render(),*/
                    'bank' => $bank,
                    'gamePrice' => $this->game_double->price,
                    'gameStatus' => $this->game_double->status
                ];
                    if ($id == 1) {
                        $this->redis->publish(self::newPoints_1_channel, json_encode($returnValue));
                    } elseif ($id == 2) {
                        $this->redis->publish(self::newPoints_2_channel, json_encode($returnValue));
                    } else {
                        $this->redis->publish(self::newPoints_0_channel, json_encode($returnValue));
                    }
                return response()->json(['text' => 'Действие выполнено.', 'type' => 'success']);
            }else{
                return response()->json(['text' => 'Недостаточно средств на вашем балансе.', 'type' => 'error']);
            }
        }
}

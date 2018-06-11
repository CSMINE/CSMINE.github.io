<?php

namespace App\Http\Controllers;

use App\Bet;
use App\Game;
use App\Item;
use App\Shop;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function parseAction(Request $request)
    {
        switch($request->get('action')){
            case 'userInfo':
                $user = User::where('steamid64', $request->get('id'))->first();
                if(!is_null($user)) {
                    $games = Game::where('winner_id', $user->id)->get();
                    $wins = $games->count();
                    $gamesPlayed = \DB::table('games')
                        ->join('bets', 'games.id', '=', 'bets.game_id')
                        ->where('bets.user_id', $user->id)
                        ->groupBy('bets.game_id')
                        ->orderBy('games.created_at', 'desc')
                        ->select('games.*', \DB::raw('SUM(bets.price) as betValue'))->get();
                    $gamesList = [];
                    $i = 0;
                    foreach ($gamesPlayed as $game) {
                        $gamesList[$i] = (object)[];
                        $gamesList[$i]->id = $game->id;
                        $gamesList[$i]->win = false;
                        $gamesList[$i]->bank = $game->price;
                        if ($game->winner_id == $user->id) $gamesList[$i]->win = true;
                        if ($game->status != Game::STATUS_FINISHED) $gamesList[$i]->win = -1;
                        $gamesList[$i]->chance = round($game->betValue / $game->price, 3) * 100;
                        $i++;
                    }
                    return response()->json([
                        'username' => $user->username,
                        'avatar' => $user->avatar,
                        'votes' => $user->votes,
                        'wins' => $wins,
                        'url' => 'http://steamcommunity.com/profiles/' . $user->steamid64 . '/',
                        'winrate' => count($gamesPlayed) ? round($wins / count($gamesPlayed), 3) * 100 : 0,
                        'totalBank' => $games->sum('price'),
                        'games' => count($gamesPlayed),
                        'list' => $gamesList
                    ]);
                }
                return response('Error. User not found.', 404);
                break;
            case 'itemmodal-1':
                $itemid = $request->get('id');
                $status =  Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('status');
                if ($status !== 0) {
                }
                if (is_null($itemid)) {
                }
                else {
                    $name = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('name');
                    $inventoryId = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('inventoryId');
                    $classid = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('classid');
                    $price = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('price');
                    $steam_price = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('steam_price');
                    $rarity = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('rarity');
                    $quality = Shop::where('id', $itemid)->where('status', Shop::ITEM_STATUS_FOR_SALE)->pluck('quality');

                    /*$jsonInventory = file_get_contents('http://steamcommunity.com/profiles/76561198343147495/inventory/json/730/2?l=russian');
                    $items = json_decode($jsonInventory, true);*/

                    $link = 'steam://rungame/730/76561198343147495/+csgo_econ_action_preview%20S76561198177996866A5262835061D14620593727355958549';
                     return response()->json([
                        'id' => $itemid,
                        'name' => $name,
                        'inventoryId' => $inventoryId,
                        'classid' => $classid,
                        'price' => $price,
                        'steam_price' => $steam_price,
                        'rarity' => $rarity,
                        'quality' => $quality,
                        'link' => $link
                    ]);
                }
                return response('Error. Item not found.', 404);
                break;
            case 'shopSort':
                $options = $request->get('options');
                if(empty($options['searchRarity'])) $options['searchRarity'] = [ "Тайное", "Засекреченное", "Запрещенное", "Промышленное качество", "Армейское качество", "Ширпотреб", "базового класса", "экзотичного вида" ,"высшего класса", "примечательного типа", "Контрабандное" ];
                if(empty($options['searchType'])) $options['searchType'] = [ "Нож", "Винтовка", "Дробовик", "Пистолет", "Пистолет-пулемёт", "Снайперская винтовка", "Пулемёт", "Контейнер", "Наклейка", "Инструмент", "Нож", "Набор музыки", "Ключ", "Пропуск", "Подарок", "Ярлык" ];
                if(is_array($options['searchQuality'])){
                    if(empty($options['searchQuality'])) $options['searchQuality'] = [ "Прямо с завода", "Немного поношенное", "После полевых испытаний", "Поношенное", "Закаленное в боях", "металлическая" ];
                    $items = Shop::where('name', 'like', '%'.$options['searchName'].'%')
                        ->whereBetween('price', [$options['minPrice'], $options['maxPrice'] + 1])
                        ->whereIn('type', $options['searchType'])
                        ->whereIn('rarity', $options['searchRarity'])
                        ->whereIn('quality', $options['searchQuality'])
                        ->orderBy('price', $options['sort'])
                        ->where('status', Shop::ITEM_STATUS_FOR_SALE)
                        ->groupBy(['name','quality'])
                        ->get(['id','name', 'inventoryId', 'classid', 'price', 'steam_price', 'rarity', 'quality', 'type', \DB::raw('COUNT(`classid`) as picscount')]);
                }else
                    $items = Shop::where('name', 'like', '%'.$options['searchName'].'%')
                    ->whereBetween('price', [$options['minPrice'], $options['maxPrice'] + 1])
                    ->whereIn('type', $options['searchType'])
                    ->whereIn('rarity', $options['searchRarity'])
                    ->orderBy('price', $options['sort'])
                    ->where('status', Shop::ITEM_STATUS_FOR_SALE)
                    ->groupBy(['name','quality'])
                    ->get(['id','name', 'inventoryId', 'classid', 'price', 'steam_price', 'rarity', 'quality', 'type', \DB::raw('COUNT(`classid`) as picscount')]);
                return $items->toArray();
                break;
        }
    }
}
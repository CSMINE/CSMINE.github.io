<?php

namespace App\Http\Controllers;

use App\Item;
use App\Price;
use App\Services\SteamItem;
use App\Shop;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    const NEW_ITEMS_CHANNEL = 'items.to.sale';
    const GIVE_ITEMS_CHANNEL = 'items.to.give';

    const PRICE_PERCENT_TO_SALE = 115;   // Процент от цены steam 
    const LINK_TO_REVIEWS = '';

    public function index()
    {
        return view('shop_layout');
    }

    public function history()
    {
        $items = Shop::where('buyer_id', $this->user->id)->orderBy('buy_at', 'desc')->get();
        return view('pages.historyShop', compact('items'));
    }

 

    public function setItemStatus(Request $r)
    {
        $item = Shop::find($r->get('id'));
        if(!is_null($item)){
            $item->status = $r->get('status');
            $item->save();
            return $item;
        }
        return response()->json(['success' => false]);
    }

       public function addItemsToSale()
    {
        $jsonItems = $this->redis->lrange(self::NEW_ITEMS_CHANNEL, 0, -1);
        foreach($jsonItems as $jsonItem){
            $items = json_decode($jsonItem, true);
            foreach($items as $item) {
                $dbItemInfo = Item::where('market_hash_name', $item['market_hash_name'])->first();
                if (is_null($dbItemInfo)) {
                    $itemInfo = new SteamItem($item);
                    $item['steam_price'] = $itemInfo->price;
                    $item['price'] = round($item['steam_price']/100 * self::PRICE_PERCENT_TO_SALE);
                    $shop = Shop::create($item);
                }else{
                    $item['steam_price'] = $dbItemInfo->price;
                    $item['price'] = round($item['steam_price']/100 * self::PRICE_PERCENT_TO_SALE);
                    $shop = Shop::create($item);
                }
            }
            $this->redis->lrem(self::NEW_ITEMS_CHANNEL, 1, $jsonItem);
        }
        return response()->json(['success' => true]);
    }

    public function buyItem(Request $request)
    {
        $id = $request->get('id');
        $item = \DB::table('shop')->where('id', $id)->where('status', '0')->get();
        $status =  \DB::table('shop')->where('id', $id)->where('status', '0')->pluck('status');
        $price =  \DB::table('shop')->where('id', $id)->where('status', '0')->pluck('price');
        $inventoryId = \DB::table('shop')->where('id', $id)->where('status', '0')->pluck('inventoryId');
        if(!is_null($item)){
        if($status != '0') return response()->json(['success' => false, 'msg' => 'Предмет уже куплен!']);
        if($this->user->accessToken == "") return response()->json(['success' => false, 'msg' => 'Вставьте ссылку на обмен!']);
            if($this->user->money >= $price){
                \DB::table('shop')->where('id', $id)->update(['status' => '1', 'buyer_id' => $this->user->id, 'buy_at' => Carbon::now()]);
                $this->sendItem($id, $inventoryId);
                $this->user->money = $this->user->money - $price;
                $this->user->save();
                return response()->json(['success' => true, 'msg' => 'Вы успешно купили предмет!']);
            }else{
                return response()->json(['success' => false, 'msg' => 'Ошибка! У вас недостаточно средств для покупки.']);
            }
        }else{
            return response()->json(['success' => false, 'msg' => 'Ошибка! Предмет уже куплен!']);
        }
    }

    public function sendItem($id, $inventoryId)
    {
        $value = [
            'id' => $id,
            'itemId' => $inventoryId,
            'partnerSteamId' => $this->user->steamid64,
            'accessToken' => $this->user->accessToken,
        ];

        $this->redis->rpush(self::GIVE_ITEMS_CHANNEL, json_encode($value));
    }
}
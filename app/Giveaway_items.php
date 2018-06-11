<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Giveaway_items extends Model
{
    protected $table = 'giveaway_items';

    public $timestamps = false;

    protected $fillable = ['name', 'inventoryId', 'classid', 'price', 'rarity', 'type', 'id'];

    public static function getClassRarity($type){
        switch ($type) {
            case 'Армейское качество':      return 'milspec'; break;
            case 'Запрещенное':             return 'restricted'; break;
            case 'Засекреченное':           return 'classified'; break;
            case 'Тайное':                  return 'covert'; break;
            case 'Ширпотреб':               return 'common'; break;
            case 'Промышленное качество':   return 'uncommon'; break;
        }
    }
}
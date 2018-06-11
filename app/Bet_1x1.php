<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet_1x1 extends Model
{
	    protected $table = 'bets_1x1';
	    protected $fillable = ['id'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function game()
    {
        return $this->belongsTo('App\Game_1x1');
    }
}

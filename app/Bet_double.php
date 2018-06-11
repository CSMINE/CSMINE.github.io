<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet_double extends Model
{
	protected $table = 'bets_double';
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function game()
    {
        return $this->belongsTo('App\Game_double');
    }
}

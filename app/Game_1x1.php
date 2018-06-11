<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Game_1x1 extends Model
{
    const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_PRE_FINISH = 2;
    const STATUS_FINISHED = 3;
    const STATUS_ERROR = 4;

    const STATUS_PRIZE_WAIT_TO_SENT = 0;
    const STATUS_PRIZE_SEND = 1;
    const STATUS_PRIZE_SEND_ERROR = 2;

    protected $table = 'games_1x1';
    protected $fillable = ['rand_number','id'];

        public function winner()
    {
        return $this->belongsTo('App\User');
    }
    public function users()
    {
        return \DB::table('games_1x1')
            ->join('bets_1x1', 'games_1x1.id', '=', 'bets_1x1.game_id')
            ->join('users', 'bets_1x1.user_id', '=', 'users.id')
            ->where('games_1x1.id', $this->id)
            ->groupBy('users.username')
            ->select('users.*','bets_1x1.number')
            ->get();
    }
	
    public function bets()
    {
        return $this->hasMany('App\Bet_1x1');
    }
}

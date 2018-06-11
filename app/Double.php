<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Double extends Model
{
    const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_PRE_FINISH = 2;
    const STATUS_FINISHED = 3;
    const STATUS_ERROR = 4;
    protected $table = 'double';
    protected $fillable = ['wobble'];

    public function users()
    {
        return \DB::table('double')
            ->join('bets_double', 'double.id', '=', 'bets_double.game_id')
            ->join('users', 'bets_double.user_id', '=', 'users.id')
            ->where('double.id', $this->id)
            ->groupBy('users.username')
            ->select('users.*')
            ->get();
    }
    public function bets()
    {
        return $this->hasMany('App\DoubleBets', 'id');
    }

}


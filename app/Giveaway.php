<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_PRE_FINISH = 2;
    const STATUS_FINISHED = 3;
    const STATUS_ERROR = 4;
    protected $table = 'giveaway';
    protected $fillable = ['max_user','items'];

    public static function win_name($id)
    {
        return \DB::table('users')->where('id', $id)->pluck('username');
    }
}


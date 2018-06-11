<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Config extends Model{

    protected $table = 'config';

    protected $fillable = ['sitename', 'keywords', 'description', 'socket_ip', 'curs', 'usd', 'online', 'mrh_ID', 'mrh_secret1', 'mrh_secret2'];

    public $timestamps = false;

}
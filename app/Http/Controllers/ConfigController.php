<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ConfigController extends Controller
{
    public function __construct() {
        $this->config = $this->getConfig();
    }
	
	public function getIpSocket()
    {
		$socket_ip = $this->config->socket_ip;
        return response()->json(['ip' => $socket_ip]);
    }
    
    public function getConfig() {
        $config = DB::table('config')->first();
        return $config;
    }
}

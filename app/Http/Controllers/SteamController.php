<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;

class SteamController extends Controller
{
	const API_KEY = 'C93D4197114ED13D870DAD8A5F08201A'; // Нужен для проверки на Трейдбан.
	
    private $steamAuth;

    public function __construct(SteamAuth $auth)
    {
        parent::__construct();
        $this->steamAuth = $auth;
    }
	
    public function login()
    {
        if ($this->steamAuth->validate()) {
            $steamID = $this->steamAuth->getSteamId();
            $user = User::where('steamid64', $steamID)->first();
			
			$tradeban_info = 'http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key='.self::API_KEY.'&steamids='.$steamID;
			$tradeban_info = file_get_contents($tradeban_info);
			$tradeban_info = json_decode($tradeban_info, true);
			$tradeban = $tradeban_info['players'][0]['EconomyBan'];
			
            if (!is_null($user)) {
                $steamInfo = $this->steamAuth->getUserInfo();
                \DB::table('users')
                    ->where('steamid64', $steamID)
                    ->update(['username' => $this->replaceLogin($steamInfo->getNick()),
                        'avatar' => $steamInfo->getProfilePictureFull(),
						'tradeban' => $tradeban
                        //'ip' => $this->getIP()
						]);
            } else {
                $steamInfo = $this->steamAuth->getUserInfo();
                $user = User::create([
                    'username' => $this->replaceLogin($steamInfo->getNick()),
                    'avatar' => $steamInfo->getProfilePictureFull(),
                    'steamid' => $steamInfo->getSteamID(),
                    'steamid64' => $steamInfo->getSteamID64(),
					'tradeban' => $tradeban
                    //'ip' => $this->getIP(),
                ]);

            }
            Auth::login($user, true);
            return redirect('/');
        } else {
            return $this->steamAuth->redirect();
        }
    }

    public function replaceLogin($login)
    {
        if(stristr($login, 'HAPPYSKINS.RU') === FALSE) {
            $login = preg_replace('/([A-Za-z0-9а-яА-Я]+\.)+(ru|com|cоm|сom|соm|net|gl|su|red|ws|us|pro|porn)/iu', '***', $login);
        }

        return $login;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function updateSettings(Request $request)
    {
        $user = $this->user;
        if (!$request->ajax()) {
            $steamInfo = $this->_getSteamInfo($user->steamid64);
            $user->username = $steamInfo->getNick();
            $user->avatar = $steamInfo->getProfilePictureFull();
        }
        if ($token = $this->_parseTradeLink($link = $request->get('trade_link'))) {
            $user->trade_link = $link;
            $user->accessToken = $token;
            $user->save();
            if ($request->ajax())
                return response()->json(['msg' => 'Ссылка успешно сохранена', 'status' => 'success']);
            return redirect()->back()->with('success', 'Ссылка успешно сохранена');
        } else {
            if ($request->ajax())
                return response()->json(['msg' => 'Неверный формат ссылки', 'status' => 'error']);
            return redirect()->back()->with('error', 'Неверный формат ссылки');
        }
    }

    private function _parseTradeLink($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }

    public function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}

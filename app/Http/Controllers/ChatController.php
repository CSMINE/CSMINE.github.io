<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LRedis;

class ChatController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const DELETE_MSG_CHANNEL = 'del.msg';

    public function __construct()
    {
        parent::__construct();
        $this->redis = LRedis::connection();
    }

	public function banchat($steamid64) {
		$user = User::where('steamid64', $steamid64)->first();
		
		if($user->banchat == 0){
			User::where('steamid64', $user->steamid64)->update(['banchat' => '1']);
			return response()->json(['status' => 'success', 'reason' => 'Пользователь заблокирован.']);
		}else{
			return response()->json(['status' => 'error', 'reason' => 'Пользователь уже заблокирован.']);
		}
	}
	public function unbanchat($steamid64) {
		$user = User::where('steamid64', $steamid64)->first();
		
		if($user->banchat == 1){
			User::where('steamid64', $user->steamid64)->update(['banchat' => '0']);
			return response()->json(['status' => 'success', 'reason' => 'Пользователь разблокирован.']);
		}else{
			return response()->json(['status' => 'error', 'reason' => 'Пользователь не заблокирован.']);
		}
	}
	
    public static function chat()
    {
        $redis = LRedis::connection();

        $value = $redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $returnValue = NULL;
        $value = array_reverse($value);

        foreach ($value as $key => $newchat[$i]) {
            if ($i > 9) {
                break;
            }
            $value2[$i] = json_decode($newchat[$i], true);



            $value2[$i]['messages'] = str_replace("::ak::", "<img src=/front/images/smiles/ak.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::choza::", "<img src=https://vk.com/images/stickers/2925/64.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::colt::", "<img src=/front/images/smiles/colt.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::dagger::", "<img src=/front/images/smiles/dagger.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::dead::", "<img src=/front/images/smiles/dead.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::gogogo::", "<img src=/front/images/smiles/gogogo.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::hook::", "<img src=/front/images/smiles/hook.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::knife::", "<img src=/front/images/smiles/knife.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::1::", "<img src=/front/images/smiles/1.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::2::", "<img src=/front/images/smiles/2.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::3::", "<img src=/front/images/smiles/3.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::4::", "<img src=/front/images/smiles/4.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::5::", "<img src=/front/images/smiles/5.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::6::", "<img src=/front/images/smiles/6.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::7::", "<img src=/front/images/smiles/7.png>", $value2[$i]['messages']);
            $value2[$i]['messages'] = str_replace("::8::", "<img src=/front/images/smiles/8.png>", $value2[$i]['messages']);


            $returnValue[$i] = [
                'userid' => $value2[$i]['userid'],
				'steamid64' => $value2[$i]['steamid64'],
                'avatar' => $value2[$i]['avatar'],
				'colors' => $value2[$i]['colors'],
                'time' => $value2[$i]['time'],
                'time2' => $value2[$i]['time2'],
                'support' => $value2[$i]['support'],
                'messages' => $value2[$i]['messages'],
                'username' => $value2[$i]['username'],
                'admin' => $value2[$i]['admin']];

            $i++;

        }

       // if (!is_null($returnValue)) return array_reverse($returnValue);

        return array_reverse($returnValue);
    }


    public function __destruct()
    {
        $this->redis->disconnect();
    }

    public function delete_message(Request $request)
    {
        $value = $this->redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $json = json_encode($value);
        $json = json_decode($json);
        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->time2 == $request->get('messages')) {
                $this->redis->lrem(self::CHAT_CHANNEL, 1, json_encode($val));
                $this->redis->publish(self::DELETE_MSG_CHANNEL, json_encode($val));
            }
            $i++;
        }
		return response()->json(['message' => 'Сообщение удалено!', 'status' => 'success']);
    }

    public function add_message(Request $request)
    {
		
        $val = \Validator::make($request->all(), [
            'messages' => 'required|string|max:255'
        ],[
            'required' => 'Сообщение не может быть пустым!',
            'string' => 'Сообщение должно быть строкой!',
            'max' => 'Максимальный размер сообщения 255 символов.',
        ]);
        $error = $val->errors();

        if($val->fails()){
            return response()->json(['errors' => $error->first('messages')]);
        }

        $messages = $request->get('messages');
        if (\Cache::has('addmsg.user.' . $this->user->id)) return response()->json(['message' => 'Вы слишком часто отправляете сообщения!', 'status' => 'error']);
        \Cache::put('addmsg.user.' . $this->user->id, '', 0.05);
        if ($this->user->banchat == 1) {
            return response()->json(['message' => 'Вы не можете больше писать в чат. Срок: Навсегда', 'status' => 'error']);
        }
		$userbets = \DB::table('bets')->where('user_id', $this->user->id)->get();
		if(!$userbets){
		  return response()->json(['status' => 'error', 'message' => 'Вам нужно сделать одну ставку.']);
		}
		
        $support = $this->user->support;
        $admin = $this->user->is_admin;
		$vip = $this->user->is_vip;
        $avatar = $this->user->avatar;
        $userid = $this->user->id;
		$steamid64 = $this->user->steamid64;
		$colors = 'color:white;';
        if ($support) {
            $avatar = '/img/support.png';
            $support = 1;
            $admin = 0;
			$vip = 0;
        } else if($admin) {
            $support = 0;
			$admin = 1;
			$vip = 0;
            $admin = $this->user->is_admin;
        }else{
			$vip = 1;
			$support = 0;
			$admin = 0;
			$vip = $this->user->is_vip;
		}

        $username = htmlspecialchars($this->user->username);
		if ($vip) {
            $username = '[VIP]' . $username;
			$colors = 'color:#d4a248;';
        }
        $time = date('H:i', time());


        function object_to_array($data)
        {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        $words = file_get_contents(dirname(__FILE__) . '/words.json');
        $words = object_to_array(json_decode($words));

        foreach ($words as $key => $value) {
            $messages = str_ireplace($key, $value, $messages);
        }

        if ($this->user->is_admin) {
            if (substr_count($messages, '/clear')) {

                $this->redis->del(self::CHAT_CHANNEL);
                return response()->json(['message' => 'Вы отчистили чат!', 'status' => 'success']);
            }

        } else {

			if (preg_match("/href|url|http|www|.ru|.com|.net|.info|csgo|winner|ru|com|net|info|.org/i", $messages)) {
				return response()->json(['message' => 'Ссылки запрещены!', 'status' => 'error']);
            }

        }
        $returnValue = ['userid' => $userid, 'steamid64' => $steamid64, 'avatar' => $avatar, 'colors' => $colors, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => htmlspecialchars($messages), 'username' => $username, 'support' => $support, 'admin' => $admin];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
		return response()->json(['message' => 'Ваше сообщение успешно отправлено!', 'status' => 'success']);
	}
}

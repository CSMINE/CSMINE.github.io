<?php namespace App\Services\GDonate;

use DB;
use App\User;

class GDonateModel
{

    static function getInstance()
    {
        return new self();
    }


    function createPayment($gdonateId, $account, $sum, $itemsCount)
    {
		
		 return DB::table('gdonate_payments')->insert([
            'gdonateId' => $gdonateId,
            'account' => $account,
            'sum' => $sum,
            'itemsCount' => $itemsCount,
            'dateCreate' => time(),
            'status' => 0,
        ]);
		

    }

    function getPaymentByGDonateId($gdonateId)
    {
		
		return DB::table('gdonate_payments')->where('gdonateId',$gdonateId)->first();

    }

    function confirmPaymentByGDonateId($gdonateId)
    {
       return DB::table('gdonate_payments')->where('gdonateId',$gdonateId)->update([
            'status' => 1,
            'dateComplete' => time(),
        ]);
    }
    
    function getAccountByName($account)
    {
        return User::find($account);
    }
    
    function donateForAccount($account, $count)
    {
        $user = User::find($account);
		if($count >= 50 && $count < 100){
			$bonus = $count * 5 / 100;
			$user->money = $user->money + $count + $bonus;
		}else if($count >= 100 && $count < 250) {
			$bonus = $count * 10 / 100;
			$user->money = $user->money + $count + $bonus;
		}else if($count >= 250 && $count < 500) {
			$bonus = $count * 15 / 100;
			$user->money = $user->money + $count + $bonus;
		}else if($count >= 500 && $count < 1000) {
			$bonus = $count * 20 / 100;
			$user->money = $user->money + $count + $bonus;
		}else if($count >= 1000) {
			$bonus = $count * 25 / 100;
			$user->money = $user->money + $count + $bonus;
		}else{
			$user->money = $user->money + $count;
		}
        $user->save();
    }
}
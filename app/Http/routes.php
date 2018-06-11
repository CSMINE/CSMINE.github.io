<?php

/* GUEST ROUTES */

Route::get('/login', ['as' => 'login', 'uses' => 'SteamController@login']);
Route::get('/', ['as' => 'index', 'uses' => 'GameController@currentGame']);
Route::get('/about', ['as' => 'about', 'uses' => 'PagesController@about']);
Route::get('/top', ['as' => 'top', 'uses' => 'PagesController@top']);
Route::get('/fastgame', ['as' => 'fastgame', 'uses' => 'Game_1x1Controller@currentGame']);
Route::get('/history', ['as' => 'history', 'uses' => 'PagesController@history']);
Route::get('/history/{game}', ['as' => 'game', 'uses' => 'PagesController@game']);

/*Route::get('/genxren', ['as' => 'shop', 'uses' => 'PagesController@genxren']);*/

Route::get('/getOrderStatus', ['as' => 'getOrderStatus', 'uses' => 'PagesController@getOrderStatus']);

Route::get('/chat', ['as' => 'chat', 'uses' => 'ChatController@chat']);

/*  PAYMENT */
Route::get('/result', 'PagesController@result');
Route::get('/success', 'PagesController@success');
Route::get('/fail', 'PagesController@fail');
Route::post('/pushback', 'PagesController@pushback');

#Double
Route::get('/double', ['as' => 'double', 'uses' => 'Game_doubleController@currentGame']);;

Route::post('/newItemsGiveaway', 'GiveawayController@addItemsToGiveaway');

/* SHOP ROUTES */
Route::get('/withdraw', ['as' => 'withdraw', 'uses' => 'PagesController@withdraw']);

/* API SITE */
Route::post('ajax', ['as' => 'ajax', 'uses' => 'AjaxController@parseAction']);
Route::post('/update', ['as' => 'update', 'uses' => 'GameController@updateStats']);
Route::post('/getsocketserver', ['as' => 'getsocket', 'uses' => 'GameController@getIpSocket']);

/* SITE AUTH ROUTES */

Route::group(['middleware' => 'auth'], function () {
    Route::get('/deposit', ['as' => 'deposit', 'uses' => 'GameController@deposit']);
    Route::post('/settings/save', ['as' => 'settings.update', 'uses' => 'SteamController@updateSettings']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'SteamController@logout']);
    //Подкрутка
    Route::post('/setWinner1x1', ['as' => 'admin.setWinner1x1', 'uses' => 'Game_1x1Controller@setWinner', 'middleware' => 'access:admin']);
    Route::get('/setWinner_double', ['as' => 'dmin.setWinner_double', 'uses' => 'Game_doubleController@setWinner_double', 'middleware' => 'access:admin']);
    //Подкрутка
    //Money
	Route::post('/addMoney', ['as' => 'add.money', 'uses' => 'GameController@addMoney']);
    Route::post('/getBalance', ['as' => 'get.balance', 'uses' => 'GameController@getBalance']);
    //SetWinner
    Route:: post('/setWinner', ['as' => 'admin.setWinner', 'uses' => 'GameController@setWinner', 'middleware' => 'access:admin']);
    //Bonus
    Route::post('/daily_bonus', 'GameController@getBonus');
	// REFERAL
    Route::get('/ref', ['as' => 'ref', 'uses' => 'ReferalController@ref']);
	Route::post('/getcoupon', ['as' => 'getcoupon', 'uses' => 'ReferalController@getcoupon']);
	Route::post('/setcoupon', ['as' => 'setcoupon', 'uses' => 'ReferalController@setcoupon']);
    //Giveaway
    Route::post('/giveaway/accept', 'GiveawayController@accept');
    Route::get('/api/addusers', ['as' => 'addusers', 'uses' => 'GiveawayController@addusers']);
    //Double
    Route::post('/addTicket_double', 'Game_doubleController@addTicket');    
	/* PAYMENT */
    Route::get('/pay', ['as' => 'pay', 'uses' => 'PagesController@pay']);
    Route::get('/skinpay', ['as' => 'Skinpay', 'uses' => 'PagesController@skinpay']);
    /*SHOP*/
    Route::post('/shop/buy', ['as' => 'shop.buy', 'uses' => 'ShopController@buyItem']);
    Route::get('/shop', ['as' => 'shop', 'uses' => 'ShopController@index']);
    Route::get('/shop/history', ['as' => 'shop.history', 'uses' => 'ShopController@history']);
});
Route::get('/getWinners_double', 'Game_doubleController@getWinners');
/* BOT ROUTES */
Route::group(['prefix' => 'api', 'middleware' => 'secretKey'], function () {
    Route::post('/checkOffer', 'GameController@checkOffer');
    Route::post('/newBet', 'GameController@newBet');
    Route::post('/setGameStatus', 'GameController@setGameStatus');
    Route::post('/setPrizeStatus', 'GameController@setPrizeStatus');
    Route::post('/getCurrentGame', 'GameController@getCurrentGame');
	Route::post('/getLastBets', 'GameController@getLastBets');
    Route::post('/getWinners', 'GameController@getWinners');
    Route::post('/getPreviousWinner', 'GameController@getPreviousWinner');
    Route::post('/newGame', 'GameController@newGame');
    Route::post('/chat', 'ChatController@apiChat');
    Route::post('/addBot', 'GameController@addBot');
	Route::post('/lastwinner', 'GameController@lastwinner');
    Route::post('/todaylucky', 'GameController@todaylucky');
    #FastGameBot
    Route::post('/checkOffer1x1', 'Game_1x1Controller@checkOffer');
    Route::post('/newBet1x1', 'Game_1x1Controller@newBet');
    Route::post('/setPrizeStatus1x1', 'Game_1x1Controller@setPrizeStatus');
    Route::post('/newGame1x1', 'Game_1x1Controller@newGame');
    Route::post('/getCurrentGame1x1', 'Game_1x1Controller@getCurrentGame');
    Route::post('/getWinners1x1', 'Game_1x1Controller@getWinners');
    #LastWinner
    Route::post('/lastwinner', 'GameController@lastwinner');
    Route::post('/todaylucky', 'GameController@todaylucky');
    Route::post('/alllucky', 'GameController@alllucky');
    //Giveaway
    Route::post('/setGameStatusGiveaway', 'GiveawayController@setGameStatusGiveaway');
    Route::post('/newItemsGiveaway', 'GiveawayController@addItemsToGiveaway');
    //Double
    Route::post('/getCurrentGame_double', 'Game_doubleController@getCurrentGame');
    Route::post('/newGame_double', 'Game_doubleController@newGame');
    Route::post('/setGameStatus_double', 'Game_doubleController@setGameStatus');
    Route::post('/getWinners_double', 'Game_doubleController@getWinners');
    Route::post('/AcceptingBets_double', 'Game_doubleController@AcceptingBets');
    /* SHOP BOT ROUTES */
     Route::post('/shop/newItems', 'ShopController@addItemsToSale');
     Route::post('/shop/setItemStatus', 'ShopController@setItemStatus');
});
/* CHAT ROUTES */
     Route::get('/shop/newItems', 'ShopController@addItemsToSale');
    Route::get('/shop/setItemStatus', 'ShopController@setItemStatus');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/add_message', ['as' => 'chat', 'uses' => 'ChatController@add_message']);
    Route::post('/delete_message', ['as' => 'chat', 'uses' => 'ChatController@delete_message']);
    Route::post('/ban_user', ['as' => 'chat', 'uses' => 'ChatController@ban_user']);
    Route::post('/unban_user', ['as' => 'chat', 'uses' => 'ChatController@unban_user']);
});

Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
	Route::get('/admin', ['as' => 'admin', 'uses' => 'Admin@index']);
    Route::get('/admin/users', ['as' => 'users', 'uses' => 'Admin@users']);
    Route::get('/admin/user/{id}', ['as' => 'users', 'uses' => 'Admin@user']);
    Route::get('/admin/game', ['as' => 'game', 'uses' => 'Admin@game']);
    Route::post('/admin/edit_site', ['as' => 'edit_site', 'uses' => 'Admin@edit_site']);
    Route::post('/admin/useredit', ['as' => 'edituser', 'uses' => 'Admin@edit_user']);
    Route::post('/admin/edit_game', ['as' => 'edit_game', 'uses' => 'Admin@edit_game']);
});

@extends('layout')

@section('content')

 <link rel="stylesheet" type="text/css" href="{{asset('front/css/1x1.css')}}">

@if(Auth::check() && $u->is_admin)
<script src="{{ asset('front/js/app_1x1_adm.js') }}" ></script>
<script src="{{ asset('front/js/clientadmin.js') }}" ></script>
@else
<script src="{{ asset('front/js/app_1x1.js') }}" ></script>
@endif


<header>
    
    @include('includes.menu') 

    <div class="banners">
        <div id="wrapper">

            <div class="user_block">
                <div class="inbox">
                    <div class="title">Последний победитель</div>
                    <div class="clear"></div>
                    <div class="avatar" id="winavatar"><img src="{{$lastava}}"></div>
                    <ul>
                        <li class="name" id="winidrest">{{$lastname}}</li>
                        <li><span class="left">Шанс:</span><span class="right" id="winchancet">{{$lastchanc}}%</span></li>
                        <li><span class="left">Выигрыш:</span><span class="right" id="winmoner">{{$lastprice}} <sub>₽</sub></span></li>
                    </ul>
                </div>
            </div>

            <div class="user_block">
                <div class="inbox owl-carousel" id="lucky">

                    <div class="item">
                        <div class="title">Удачливый сегодня</div>
                        <div class="clear"></div>
                        <div class="avatar" id="denb-4"><img src="{{$luckyava}}"></div>
                        <ul>
                            <li class="name" id="denb-1">{{$luckyname}}</li>
                            <li><span class="left">Шанс:</span><span class="right" id="denb-3">{{$luckychanc}}%</span></li>
                            <li><span class="left">Выигрыш:</span><span class="right" id="denb-2">{{$luckyprice}} <sub>₽</sub></span></li>
                        </ul>
                    </div>

                    <div class="item">
                        <div class="title">Удачливый за всё время</div>
                        <div class="clear"></div>
                        <div class="avatar" id="vsegda-4"><img src="{{$allluckyava}}"></div>
                        <ul>
                            <li class="name" id="vsegda-1">{{$allluckyname}}</li>
                            <li><span class="left">Шанс:</span><span class="right" id="vsegda-3">{{$allluckychanc}}%</span></li>
                            <li><span class="left">Выигрыш:</span><span class="right" id="vsegda-2">{{$allluckyprice}} <sub>₽</sub></span></li>
                        </ul>
                    </div>

                </div>
            </div>
            @if($kolvo != 0 )
            <div class="giveaway">
                <div class="inbox">
                    <div class="left">
                        @foreach(json_decode($giveaway->items) as $item)
                        <div class="title">{{$item->name}}<span>{{$item->price}} <sub>₽</sub></span></div>
                        @endforeach
                        <div class="users" id="carousel">
                            <div class="es-carousel">
                                <ul>
                                    @foreach($giveaway_users as $user)
                                    <li>
                                        <a href="#" data-profile="{{$user->steamid64}}"><img src="{{$user->avatar}}"></a>
                                    </li>
                                    @endforeach
                            </ul>
                            </div>
                        </div>
                        <div class="text">Всего: <span>{{count($giveaway_users)}} / {{$giveaway->max_user}}</span></div>
                    </div>
                    <div class="right">
                         @foreach(json_decode($giveaway->items) as $item)
                        <div class="pic"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{$item->classid}}/136fx127f"></div>
                         @endforeach
                        <a href="#" class="participate btn orange hoax-button">Учавствовать</a>
                        <!--<a href="javascript://" onclick="$('#giveaway').arcticmodal()" class="history">История розыгрышей</a>-->
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    
    <div class="rooms">
        <div id="wrapper">
            <div class="inner">

                <div class="tab">
                    <div class="inbox blue">
                        <div class="left">
                            <div class="title">
                                <div class="name">Classic Game</div>
                                <div class="tooltip" rel="tooltip" title="Минимальная сумма депозита — 1 ₽.<br>Максимальный депозит — 20 предм.<br>Время каждого раунда — 1 мин 30 сек.">i</div>
                            </div>
                            <div class="live"><span class="text">На кону — <b id="bank_jackpot">{{ round($game->price) }}</b> <sub>₽</sub></span><span class="rate jackpot" style="display: none;">+0 <sub>₽</sub></span></div>
                            <a href="/" class="btn blue">Перейти к игре</a>
                        </div>
                        <div class="right"><img src="{{asset('front/images/classic_game_pic.png')}}"></div>
                    </div>
                </div>

                <div class="tab">
                    <div class="inbox green">
                        <div class="left">
                            <div class="title">
                                <div class="name">Fast Game</div>
                                <div class="tooltip" rel="tooltip" title="Минимальная сумма депозита — 5 ₽.<br>Максимальное количество предметов составляет 15 штук.">i</div>
                            </div>
                            <div class="live"><span class="text">Розыгрыш — 0 <sub>₽</sub></span></div>
                             <a href="/fastgame" class="btn green">Перейти к игре</a>
                        </div>
                        <div class="right"><img src="{{asset('front/images/duel_game_pic.png')}}"></div>
                    </div>
                </div>

                <div class="tab">
                    <div class="inbox red">
                        <div class="left">
                            <div class="title">
                                <div class="name">Double Game</div>
                                <div class="tooltip" rel="tooltip" title="Игроки могут ставить на чёрное, красное или зелёное.<br>В качестве ставок используются монеты.">i</div>
                            </div>
                            <div class="live"><span class="text">На кону — <b id="bank_double">0</b> <sub>₽</sub></span><span class="rate double" style="display: none;">+0 <sub>₽</sub></span></div>
                            <a href="/double" class="btn red">Испытать удачу</a>
                        </div>
                        <div class="right"><img src="{{asset('front/images/double_game_pic.png')}}"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    
</header>

		<div id="wrapper">
		
<div class="top_page" style="
    background: #2b2f33;
">
        <div class="numb">
            <ul>
                <center><li style="
    margin-left: 64px;
    opacity: 1;
">FASTGAME</li></center>
            </ul>
        </div>
        <div class="numb">
        </div>
    </div>

        <div class="fast-games-container"> 
        <ul style="transition: 0.7s ease-out; transform: translate3d(0px, 0px, 0px);" id="game">
		<div class="fast-room-header"> 
			<div class="fast-room-description"> 
				<p>Fast game - быстрые игры для трех игроков.</p>
				<p>В этой игре у вас всегда хорошие шансы на победу!</p>
				<p>Делай ставку и забери ставку двух других игроков!</p>
				 </div>
			<div class="fast-room-rules"> 
				<div class="game-info-fast-rules"> 
					<div class="fast-room-rule-item"><span class="rule-item-name">Количество игроков:</span><span class="rule-item-value">3</span></div>
					<div class="fast-room-rule-item"><span class="rule-item-name">Размер ставки:</span><span class="rule-item-value">1-900P</span></div>
					<div class="fast-room-rule-item"><span class="rule-item-name">Предметов:</span><span class="rule-item-value">1-10</span></div>
					 </div>
				 </div>
				 @if(!Auth::guest())
				@if(empty($u->accessToken))
					<div class="game-info-fast-state"> 
				<div class="buttons"> <a class="btn-yellow fast-bet-button poplight no-link @if(empty($u->accessToken)) no-link @endif"
				style="cursor: pointer;"  rel="popup_name-5">Сделать ставку</a> </div></div>
					@else
					<div class="game-info-fast-state"> 
				<div class="buttons"> <a class="btn-yellow fast-bet-button" target="_blank" href="https://steamcommunity.com/tradeoffer/new/?partner=419794500&token=0tiwgewg">Сделать ставку</a> </div></div>					@endif
				 @else
			<div class="game-info-fast-state"> 
				<div class="buttons"> <a class="btn-yellow fast-bet-button" href="/login">Войти</a> </div>
				 </div>
				 @endif
			 </div>
        
			<div class="trade_link linkMsg" style="
    margin-top: 0px; display: none;
">
            <div class="title"><span>Введите Вашу ссылку на обмен</span> <a href="http://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">Узнать ссылку</a></div>
            <div class="inputs">
                <input type="text" name="" placeholder="Вставьте ссылку на обмен...">
                <button class="save-link2">Сохранить</button>
            </div>
        </div> 

			 <div id="suda">
				<div class="fast-game"id="game_{{$game1x11->id}}">

	<div class="game-fast-container"> 
		<ul class="players-percent" style="overflow: visible;list-style-type: none;">
			@include('includes.bet_1x1_1')
			@include('includes.bet_1x1_2')
			@include('includes.bet_1x1_3')
		</ul>
		<ul class="fast-game-trades-container" style="overflow: visible;list-style-type: none;">
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_1">
				@include('includes.bet_1x1_11')
				</ul>
				<div class="fast-room-trade-info-container">~<span id="price_1">{{ round($price_1) }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span class="chance_{{$users_1}}" id="chance_1">{{ $chance_1 }}</span>% @if(Auth::check() && $u->is_admin) <a href="#" id="podkryt_1" onclick="setwinner1x1({{$users_1}});">###</a> @else @endif</div>
				 </li>
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_2">
				@include('includes.bet_1x1_22')
				</ul>
				<div class="fast-room-trade-info-container">~<span id="price_2">{{ round($price_2) }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span class="chance_{{$users_2}}" id="chance_2">{{ $chance_2 }}</span>% @if(Auth::check() && $u->is_admin) <a href="#" id="podkryt_2" onclick="setwinner1x1({{$users_2}});">###</a> @else @endif</div>
				 </li>
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_3">
				@include('includes.bet_1x1_33')
				</ul>
				<div class="fast-room-trade-info-container">~<span id="price_3">{{ round($price_3) }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span class="chance_{{$users_3}}" id="chance_3">{{ $chance_3 }}</span>% @if(Auth::check() && $u->is_admin) <a href="#" id="podkryt_3" onclick="setwinner1x1({{$users_3}});">###</a> @else @endif</div>
				 </li>
		</ul>
		<div class="fast-game-stats" id="game_end"> 
			<div class="fast-game-state in-progress active"> <span>На кону:</span><span class="game-price"><span id="bank1">{{ round($game1x11->price) }}</span> руб.</span> </div>
			<div class="fast-game-state finish"> 
				<div>Победитель:</div>
				<div class="game-winner"></div>
				<div class="game-winner-fields"> 
					<p> <span class="game-winner-field">Выигрыш:</span><span class="game-price">0 руб.</span> </p>
					<p> <span class="game-winner-field">Шанс:</span> <span class="player-chance"></span> </p>
					 </div>
				 </div>
			 </div>
		 </div>
</div>
@forelse($gamese as $game1x1)
<div class="fast-game" id="game_{{$game1x1->id}}">
	<div class="game-top"> 
		<div class="game-header"> 
			<div class="game-title">Игра №<span class="game-num">{{ $game1x1->id }}</span></div>
			 </div>
		 </div>
	<div class="game-fast-container"> 
		<ul class="players-percent">
		@if($game1x1->random == 0)
			<li class="players-percent-block"> 
				<div class="players-roulette-container">
				@if($game1x1->winner->number == 1)
				<div class="player first-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@elseif($game1x1->winner->number == 2)
				<div class="player second-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@else
				<div class="player third-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@endif 
				</div>
			</li>
			<li class="players-percent-block"> 
				<div class="players-roulette-container"> 
					@if($game1x1->loser->number == 1)
				<div class="player first-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@elseif($game1x1->loser->number == 2)
				<div class="player second-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@else
				<div class="player third-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@endif
				</div>
			</li>
		@else
			<li class="players-percent-block"> 
				<div class="players-roulette-container"> 
				@if($game1x1->loser->number == 1)
				<div class="player first-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@elseif($game1x1->loser->number == 2)
				<div class="player second-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@else
				<div class="player third-player " style="background-image: url({{$game1x1->loser->avatar}});"></div>
				@endif 
				</div>
			</li>
			<li class="players-percent-block"> 
				<div class="players-roulette-container"> 
				@if($game1x1->winner->number == 1)
				<div class="player first-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@elseif($game1x1->winner->number == 2)
				<div class="player second-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@else
				<div class="player third-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@endif 
				</div>
			</li>
		@endif
			
			<li class="players-percent-block"> 
				<div class="players-roulette-container"> 
					@if($game1x1->winner->number == 1)
				<div class="player first-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@elseif($game1x1->winner->number == 2)
				<div class="player second-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@else
				<div class="player third-player fast-winner" style="background-image: url({{$game1x1->winner->avatar}});"></div>
				@endif 
				</div>
			</li>
		</ul>
		<ul class="fast-game-trades-container" style="overflow: visible;list-style-type: none;">
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;">
				@if(empty(json_decode($game1x1->bet1->items)))
				@else
				@foreach(json_decode($game1x1->bet1->items) as $i)
				<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/23fx23f);">
				<div class="item_info darkBlueBg">
					<div class="item_info_img_wrap">
						<div class="item_info_img">
							<img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/110fx100f" alt="{{ $i->name }}">
						</div>
					</div>
					<div class="item_info_description">
						<div class="item_info_name">{{ $i->name }}</div>
						<div class="item_info_price">{{ $i->price }} руб.</div>
					</div>
					<div class="item_owner">
						<div class="item_owner_img">
							<img src="{{$game1x1->bet1->avatar}}">
						</div>
					</div>
				</div></li>
				@endforeach
				@endif
				</ul>
				<div class="fast-room-trade-info-container">~<span>{{ $game1x1->price1 }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span>{{ $game1x1->chance1 }}</span>%</div>
				 </li>
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;">
				@if(empty(json_decode($game1x1->bet2->items)))
				@else
				@foreach(json_decode($game1x1->bet2->items) as $i)
				<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/23fx23f);">
				<div class="item_info darkBlueBg">
					<div class="item_info_img_wrap">
						<div class="item_info_img">
							<img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/110fx100f" alt="{{ $i->name }}">
						</div>
					</div>
					<div class="item_info_description">
						<div class="item_info_name">{{ $i->name }}</div>
						<div class="item_info_price">{{ $i->price }} руб.</div>
					</div>
					<div class="item_owner">
						<div class="item_owner_img">
							<img src="{{$game1x1->bet2->avatar}}">
						</div>
					</div>
				</div></li>
				@endforeach
				@endif
				</ul>
				<div class="fast-room-trade-info-container">~<span>{{ $game1x1->price2 }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span>{{ $game1x1->chance2 }}</span>%</div>
				 </li>
			<li class="fast-game-trade"> 
				<ul class="fast-game-trade-items-container" style="overflow: visible;">
				@if(empty(json_decode($game1x1->bet3->items)))
				@else
				@foreach(json_decode($game1x1->bet3->items) as $i)
				<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/23fx23f);">
				<div class="item_info darkBlueBg">
					<div class="item_info_img_wrap">
						<div class="item_info_img">
							<img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/110fx100f" alt="{{ $i->name }}">
						</div>
					</div>
					<div class="item_info_description">
						<div class="item_info_name">{{ $i->name }}</div>
						<div class="item_info_price">{{ $i->price }} руб.</div>
					</div>
					<div class="item_owner">
						<div class="item_owner_img">
							<img src="{{$game1x1->bet3->avatar}}">
						</div>
					</div>
				</div></li>
				@endforeach
				@endif
				</ul>
				<div class="fast-room-trade-info-container">~<span>{{ $game1x1->price3 }}</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span>{{ $game1x1->chance3 }}</span>%</div>
				 </li>
		</ul>
		<div class="fast-game-stats"> 
			<div class="fast-game-state finish active"> 
				<div>Победитель:</div>
				@if($game1x1->winner->number == 1)
				<div class="game-winner first-player">{{$game1x1->winner->username}}</div>
				@elseif($game1x1->winner->number == 2)
				<div class="game-winner second-player">{{$game1x1->winner->username}}</div>
				@else
				<div class="game-winner third-player">{{$game1x1->winner->username}}</div>
				@endif
				<div class="game-winner-fields"> 
					<p> <span class="game-winner-field">Выигрыш:</span><span class="game-price"> {{ round($game1x1->price) }} руб.</span> </p>
					<p> <span class="game-winner-field">Шанс:</span> <span class="player-chance">{{ $game1x1->win_chance1 }} %</span> </p>
					 </div>
				 </div>
			 </div>
		 </div>
</div>
@empty
<center><h1 style="color: #ffffff;font-size: 18px;margin-top: 21px;text-transform: uppercase;">Истории нет!</h1></center>
@endforelse
            </div>
        </ul>
    </div></div>

		@endsection	
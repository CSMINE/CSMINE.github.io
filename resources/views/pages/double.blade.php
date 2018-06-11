@extends('layout')

@section('content')

<script src="{{asset('/front/js/app_double.js')}}"></script>

<header>
   @include('includes.menu') 
<div class="banners">
    <div id="wrapper">
        
        
    <div class="user_block">
        <div class="inbox">
            <div class="title">Победитель дня</div>
            <div class="clear"></div>
            <div class="avatar" id="winner_avatar"><img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/1f/1f293d5148b962c4a52eade9e4dd0f4eee54c27e_full.jpg"></div>
            <ul>
                <li class="name" id="winner_username">-vlamich-</li>
                <li><span class="left">Выиграл разом:</span><span class="right" id="winner_win">826 <sub>₽</sub></span></li>
            </ul>
        </div>
    </div>

    <div class="user_block">
        <div class="inbox">

            <div class="item">
                <div class="title">Максимальный выигрыш</div>
                <div class="clear"></div>
                <div class="avatar"><img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/1f/1f293d5148b962c4a52eade9e4dd0f4eee54c27e_full.jpg"></div>
                <ul>
                    <li class="name">-vlamich-</li>
                    <li><span class="left">Выиграл разом:</span><span class="right">826 <sub>₽</sub></span></li>
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
                <a href="javascript://" onclick="$('#giveaway').arcticmodal()" class="history">История розыгрышей</a>
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
                         <div class="tooltip" rel="tooltip" title="Минимальная сумма депозита — 1 ₽.<br>Максимальное количество предметов составляет 10 штук.">i</div>
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
<!-- <middle> -->

    <div class="double_game">

        <div class="top_page">
            <div class="center">Начало через: <span id="time_double">00:30</span></div>
        </div>

        <div class="line">
            <div class="arrow"></div>
            <div class="inbox">
                <div class="picture" id="case" style="background-position: -465px 0px;"></div>
            </div>
        </div>

        <div class="last">
            <div class="text">Последние результаты:</div>
            <div class="games" id="past">
                @foreach($gamers as $game1)
                    <div data-rollid="{{ $game1->id }}" class="ball ball-{{$game1->won_const}}">{{ $game1->win_num }}</div>
                @endforeach
            </div>
        </div>


    @if(!Auth::guest() && $u->is_admin==1)
    <div class="panel2">
    <form action="/setWinner_double" method="GET" style="float: left;padding: 10px 20px 11px;">
    <div class="form" style="margin-top: 14px;margin-left: 14px;">
    <input textarea="" placeholder="Выберите число" name="id" cols="50" maxlength="2" value="" autocomplete="off" style="height: 37px;border-radius: 5px 5px 0px 0px;border: none;background: #191b1d;padding-left: 14px;width: 543%;color: #fff;" class="form-control text-center">
    <input type="submit" id="submit" class="btn" value="Подкрутить" style="margin-top: 1px;width: 550%;border-radius: 0px 0px 5px 5px;">
    </div>
    </form>
    </div>
    @endif



@if(!Auth::guest())
        <div class="panel">
            <div class="balance">Баланс: <span id="balance">{{$u->money}}</span><div class="num"><span></span><a onclick="$('#deposit_money').arcticmodal(); return false"></a></div></div>
            <div class="clear"></div>
            <div class="input"><input type="text" name="" placeholder="0" id="betAmount"></div>
            <ul>
                <li><a onclick="clearss();">Очистить</a></li>
                <li><a onclick="">Последняя</a></li>
                <li><a onclick="one();">+10</a></li>
                <li><a onclick="onet();">+100</a></li>
                <li><a onclick="oneh();">+500</a></li>
                <li><a onclick="onek();">+1000</a></li>
                <li><a onclick="half();">1/2</a></li>
                <li><a onclick="double();">X2</a></li>
                <li><a onclick="max();">Макс.</a></li>
            </ul>
        </div>
@endif
        <div class="rates">

            <div class="block red">
                <div class="title" onclick="addTicket_double(1, this)" id="red-button">1 — 7<span>2x</span></div>
<!--                <div id="red_leader">
                    <div class="highest">
                    <img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/3f/3f0a263937ffd88b72dffcafac7d6aaa401fabe9_full.jpg">
                    <ul>
                        <li>Лидер</li>
                        <li><a href="#">kupich HAPPYSKINS.RU</a></li>
                    </ul>
                        <div class="points">1</div>
                    </div>
                </div>-->
                
                <div class="total">Итого: <span id="bank_1">{{$obw1}}</span></div>
                <div id="bet_1">
                    @foreach($bets as $bet)
                        @include('includes.bet1')
                    @endforeach
                </div>
            </div>

            <div class="block green">
                <div class="title" onclick="addTicket_double(0, this)" id="green-button">0<span>14x</span></div>
<!--                <div id="green_leader">
                    <div class="highest">
                    <img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/3f/3f0a263937ffd88b72dffcafac7d6aaa401fabe9_full.jpg">
                    <ul>
                        <li>Лидер</li>
                        <li><a href="#">kupich HAPPYSKINS.RU</a></li>
                    </ul>
                        <div class="points">1</div>
                    </div>                
                </div>-->
                <div class="total">Итого: <span id="bank_0">{{$obw0}}</span></div>
                <div id="bet_0">
                    @foreach($bets as $bet)
                            @include('includes.bet0')
                    @endforeach
                </div>
            </div>

            <div class="block black">
                <div class="title" onclick="addTicket_double(2, this)" id="black-button">8 — 14<span>2x</span></div>
<!--                <div id="black_leader">
                    <div class="highest">
                    <img src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/3f/3f0a263937ffd88b72dffcafac7d6aaa401fabe9_full.jpg">
                    <ul>
                        <li>Лидер</li>
                        <li><a href="#">kupich HAPPYSKINS.RU</a></li>
                    </ul>
                        <div class="points">1</div>
                    </div>
                </div>-->
                <div class="total">Итого: <span id="bank_2">{{$obw2}}</span></div>
                <div id="bet_2">
                    @foreach($bets as $bet)
                        @include('includes.bet2')
                    @endforeach
                </div>
            </div>

        </div>

    </div>

<!-- </middle> -->
</div>


@endsection
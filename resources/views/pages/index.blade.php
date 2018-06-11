@extends('layout')

@section('content')

<header >
    
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
<!-- <middle> -->

    <div class="top_page">
        <div class="numb">
            <ul>
                <li>Номер игры:</li>
                <li id="roundId">#{{ $game->id }}</li>
            </ul>
        </div>
        <div class="numb">
            <ul>
                <li>Банк:</li>
                <li id="roundBank">{{ round($game->price) }} <sub>₽</sub></li>
            </ul>
        </div>
    </div>
    
    <div class="panel" id="game_body">
    
        <div class="top">
            <div class="progress">
                <div class="numb">{{ $game->itemsCount }} / 100</div>
                <div class="line" style="width: {{ (100/150) * $game->itemsCount }}%;"></div>
            </div>
            <div class="text">или через</div>
            <div class="timer">
                <div class="min_0">0</div>
                <div class="min_1">1</div>
                <span>:</span>
                <div class="sec_0">3</div>
                <div class="sec_1">0</div>
            </div>
        </div>
        @if(!Auth::guest())
        <div class="bottom">
            <ul>
                <li id="myItems">Вы вложили в игру <span>{{ $user_items }}</span> (из 20) предметов</li>
                <li>Минимальная сумма депозита — 1 ₽. Максимальный депозит — 20 предметов.<br>Время каждого раунда — 1 мин 30 сек</li>
            </ul>
            
            <div class="input-group" style="display: inline-block;margin-left: 120px;margin-top: 14px;">
                <input type="text" id="tsum" placeholder="0.00" pattern="^[ 0-9.]+$" maxlength="5" style="width:80px">
                <button type="submit" class="btn-add-balance @if(empty($u->accessToken)) no-link @endif" onclick="bticks(1,this)">Поставить</button>
            </div>
            
            <div class="right">
                <div class="chance" rel="tooltip" title="Ваш шанс" id="myChance">{{ $user_chance }}%</div>
                <a href="/deposit"  target="_blank" class="btn orange @if(empty($u->accessToken)) no-link @endif">Внести предметы</a>
            </div>
        </div>
        @endif
    </div> 

    <div class="panel" id="slider" style="display: none;">
    
        <div class="roulette">
            <div class="inbox">
                <div class="rotate">

                </div>
            </div>
        </div>

        <div class="arrow"></div>

        <div class="info">
            <ul>
                <li>Выигрышный билет: <span class="ticket">???</span></li>
                <li>Победитель: <a class="winner">???</a></li>
            </ul>
            <ul>
                <li>Число раунда: <span class="round_hash">???</span></li>
            </ul>
            <div class="right">
                <div class="timer_ng timer">
                    <div class="sec_0">3</div>
                    <div class="sec_1">0</div>
                </div>
                <a href="/deposit"  target="_blank" class="btn orange @if(empty($u->accessToken)) no-link @endif">Внести первым</a>
            </div>
        </div>

    </div>

        <div class="partlines" @if(!isset($players[0])) style="display: none;" @endif id="colors">
            @foreach($items as $user)
                <div class="color_{{ $user->place}}" style="width: {{ round($user->chance) }}%;"></div>
            @endforeach
        </div>

        <div class="parts" id="parts" @if(!isset($players[0])) style="display: none;" @endif>
            @foreach($items as $user)
            <div class="item color_{{ $user->place }}" style="display: inline-block; width: 98px;">
                <div class="inbox">
                    <div class="av"><img src="{{ $user->avatar }}"></div>
                    <div class="chance">{{ $user->chance }}%</div>
                </div>
            </div>
            @endforeach
        </div>
     
        <div class="parts" id="parts" style="display:  none;">
            </div>
            <div class="trade_link linkMsg" style="display: none;">
            <div class="title"><span>Введите Вашу ссылку на обмен</span> <a href="http://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">Узнать ссылку</a></div>
            <div class="inputs">
                <input type="text" name="" placeholder="Вставьте ссылку на обмен...">
                <button class="save-link2">Сохранить</button>
            </div>
        </div> 
     
<div id="bets">

        @foreach($bets as $bet)
            @if(Auth::check() && $u->is_admin)
                @include('includes.bet_admin')
                @else
                @include('includes.bet')
            @endif
        @endforeach
 </div>
     
    <div class="rate">
        <div class="top">
            <div class="av color_20"><img src="{{asset('front/images/bonusbot.jpg')}}"></div>
            <ul>
                <li><a class="username">БОНУС</a> внес депозит на сумму</li>
                <li><span>2 <sub>руб</sub></span></li> 
            </ul>   
          </div>
        <div class="items">
          <div class="item">
            <div class="inbox ">
              <div class="pic"><img src="{{asset('front/images/bonus.png')}}"></div>
              <div class="price">2 <sub>руб</sub></div>
            </div>
          </div>
        </div>
        <div class="clear"></div>
    </div>


    <div class="start">
        <div class="title">ИГРА НАЧАЛАСЬ! ВНОСИТЕ ДЕПОЗИТЫ!</div>
        <div class="title2">Сделай ставку первым и получи <b>шанс к победе +2%</b></div>
        <div class="hash">
            <a href="javascript://" onclick="$('#fair').arcticmodal()" class="btn orange">ЧЕСТНАЯ ИГРА</a>
            <div class="text" id="roundHash">Хэш раунда: {{ md5($game->rand_number) }}</div>
        </div>
    </div>

<!-- </middle> -->
</div>

<div class="chat" id="chatContainer">

    <div class="chat-header" id="chatHeader">Чат</div>

    <div class="chat-container" id="chatBody" style="height: 0;">

        <div class="inbox" id="chatScroll"><div class="scroll" id="chat_messages"></div></div>

        <div class="form">
            <textarea name="" id="sendie" placeholder="Введите текст..."></textarea>
            <div class="smiles">
                <div class="drop">
                    <div class="inbox">
                        <div class="pic" onclick="addsmile('::ak::')"><img src="{{asset('/front/images/smiles/ak.png')}}"></div>
                        <div class="pic" onclick="addsmile('::choza::')"><img src="https://vk.com/images/stickers/2925/64.png"></div>
                        <div class="pic" onclick="addsmile('::colt::')"><img src="{{asset('/front/images/smiles/colt.png')}}"></div>
                        <div class="pic" onclick="addsmile('::dagger::')"><img src="{{asset('/front/images/smiles/dagger.png')}}"></div>
                        <div class="pic" onclick="addsmile('::dead::')"><img src="{{asset('/front/images/smiles/dead.png')}}"></div>
                        <div class="pic" onclick="addsmile('::gogogo::')"><img src="{{asset('/front/images/smiles/gogogo.png')}}"></div>
                        <div class="pic" onclick="addsmile('::hook::')"><img src="{{asset('/front/images/smiles/hook.png')}}"></div>
                        <div class="pic" onclick="addsmile('::knife::')"><img src="{{asset('/front/images/smiles/knife.png')}}"></div>
                        <div class="pic" onclick="addsmile('::1::')"><img src="{{asset('/front/images/smiles/1.png')}}"></div>
                        <div class="pic" onclick="addsmile('::2::')"><img src="{{asset('/front/images/smiles/2.png')}}"></div>
                        <div class="pic" onclick="addsmile('::3::')"><img src="{{asset('/front/images/smiles/3.png')}}"></div>
                        <div class="pic" onclick="addsmile('::4::')"><img src="{{asset('/front/images/smiles/4.png')}}"></div>
                        <div class="pic" onclick="addsmile('::5::')"><img src="{{asset('/front/images/smiles/5.png')}}"></div>
                        <div class="pic" onclick="addsmile('::6::')"><img src="{{asset('/front/images/smiles/6.png')}}"></div>
                        <div class="pic" onclick="addsmile('::7::')"><img src="{{asset('/front/images/smiles/7.png')}}"></div>
                        <div class="pic" onclick="addsmile('::8::')"><img src="{{asset('/front/images/smiles/8.png')}}"></div>
                    </div>
                </div>
            </div>
            <button></button>
        </div>

    </div>

</div>

<div style="display: none;">
    <div class="box-modal" id="congratulations">

        <div class="title">Поздравляем!</div>
        <a href="javascript://" class="box-modal_close arcticmodal-close"></a>

        <p>Вы только что выиграли <b id="win_money">0 ₽</b> со взносом в размере <b id="spent_money">0 ₽</b> и шансом <b id="win_chanse">0%</b>.</p>
        <p>Вы получите предложение об обмене в течение нескольких минут.<br>Пожалуйста, примите обмен как можно быстрее.</p>

        <div class="share">
            <div class="top">Расскажите о своей победе</div>
            <div class="social">
                <a href="https://vk.com/twitch_uncle_polly" target="_blank" class="vk"></a>
            </div>
        </div>

    </div>
</div>

<div style="display: none;">
    <div class="box-modal" id="giveaway">

        <div class="title">История розыгрышей</div>
        <a href="javascript://" class="box-modal_close arcticmodal-close"></a>

        <div class="table">
            <table class="responsive" id="responsive_2">
                <tbody>
                    <tr>
                        <th class="pic"></th>
                        <th>Предмет</th>
                        <th>Победитель</th>
                        <th>Участники</th>
                    </tr>
                    <tr>
                    <td class="pic"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1835681706/200fx200f"></td>
                        <td>Наклейка | FaZe Clan | Cologne 2016</td>
                        <td> happyskins.ru</td>
                    <td>49 / 49</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
    </div>
</div>


@endsection
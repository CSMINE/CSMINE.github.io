@extends('layout')

@section('content')

<header  class="other_pages" >
   @include('includes.menu')     
</header>

<div id="wrapper">
<!-- <middle> -->

    <div class="top_page">
        <div class="center">История игр</div>
    </div>
    
    <div class="history_page" id="tabs-1"><div class="tabs">

        <div class="buttons">
            <ul>
                <li><a href="#tab-1" class="btn active">Classic Game</a></li>
                <li style="display: none;"><a href="#tab-2" class="btn">Duel Game</a></li>
                <li><a href="#tab-3" class="btn">Double Game</a></li>
            </ul>
        </div>

        <div class="tab" id="tab-1">
        @forelse($games as $game)
            <div class="rate">
                <div class="top">
                    <div class="av color_1"><img src="{{ $game->winner->avatar }}"></div>
                    <ul class="details">
                        <li><a href="javascript://" onclick="$('#profile').arcticmodal()" data-profile="{{ $game->winner->steamid64 }}">{{ $game->winner->username }}</a></li>
                        <li>Выигрыш: <span>{{ $game->price }} <sub>₽</sub></span></li>
                        <li>Выигрышный билет: <span>{{ $game->winTicket  }}</span></li>
                        <li>Всего билетов: <span>{{ round($bankTotal = $game->price * 10) }}</span></li>
                        <li>Число раунда: <span>{{$game->rand_number}}</span></li>
                        <li>Хэш раунда: <span>{{ md5($game->rand_number) }}</span> (<a href="javascript://" onclick="$('#fair').arcticmodal()" class="check">Проверить</a>)</li>
                    </ul>
                    <div class="right">
                        <div class="numb">Игра #{{ $game->id }}</div>
                        <div class="status success">Отправлено</div>
                        <div class="full"><a href="/history/{{ $game->id }}">Полная история</a></div>
                    </div>
                </div>
                <div class="items">
                {{--*/ $is = 0; /*--}}
                    @foreach (json_decode($game->won_items) as $i)
                        @if($is >=11)   {{--*/ break /*--}} @endif
                        <div class="item">
                            @if(!isset($i->img))
                            <div class="inbox {{ $i->rarity }}">
                                <div class="tooltip">
                                    <div class="pict"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f"></div>
                                    @else
                                    <div class="inbox">
                                    <div class="tooltip">
                                    <div class="pict"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                                    @endif
                                    <div class="text"><ul>
                                        <li>{{ $i->name }}</li>
                                        <li>{{$i->price}} <sub>₽</sub></li>
                                    </ul></div>
                                        <div class="pict"><img src="{{ $game->winner->avatar }}"></div>
                                </div>
                                @if(!isset($i->img))
                                <div class="pic"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f"></div>
                                @else   
                                <div class="pic"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                                @endif
                                <div class="price">{{$i->price}} <sub>₽</sub></div>
                            </div>
                        </div>  
                    {{--*/ $is++; /*--}}
                @endforeach
                    <div class="more_items" id="items_{{ $game->id }}">   
                        @foreach (json_decode($game->won_items) as $i)
                        <div class="item">
                            @if(!isset($i->img))
                            <div class="inbox {{ $i->rarity }}">
                                <div class="tooltip">
                                    <div class="pict"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f"></div>
                                    @else
                                    <div class="inbox">
                                    <div class="tooltip">
                                    <div class="pict"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                                    @endif
                                    <div class="text"><ul>
                                        <li>{{ $i->name }}</li>
                                        <li>{{$i->price}} <sub>₽</sub></li>
                                    </ul></div>
                                    <div class="pict"><img src="{{ $game->winner->avatar }}"></div>
                                </div>
                                @if(!isset($i->img))
                                <div class="pic"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f"></div>
                                @else   
                                <div class="pic"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                                @endif
                                <div class="price">{{$i->price}} <sub>₽</sub></div>
                            </div>
                        </div>
                        @endforeach
                    </div>  
                    @if(count(json_decode($game->won_items)) > 11 )
                    <div class="item">
                        <div class="inbox">
                        <div class="more" onclick="#items_{{ $game->id }}"></div>
                        </div>
                    </div> 
                    @endif
                    <div class="clear"></div>
                </div>
            </div>
            @empty
            <h5 class="center gold" style="padding: 15px 0 0;">
                Пока что не было игр.
            </h5>   
            @endforelse
        </div>

        <div class="tab" id="tab-2">
            
            <table class="duel responsive" id="responsive_3">
                <tbody>
                    <tr>
                        <th>Победил</th>
                        <th>Предметы</th>
                        <th>Всего</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                    <tr>
                        <td><img src="/front/images/t.png"><img src="avatar.png"></td>
                        <td><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div><div class="pic" rel="tooltip" title="Название предмета"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/1934436584/200fx200f"></div></td>
                        <td>215.82 <sub>₽</sub></td>
                        <td><ul class="buttons"><li><a href="javascript://" onclick="$('#view').arcticmodal()" class="btn">Смотреть</a></li></ul></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="tab" id="tab-3">
            @forelse($games_double as $game)
            <div class="double">
                <ul class="left">
                    <li>Игра №{{$game->id}}</li>
                    <li>сегодня в 13:56</li>
                </ul>
                <div class="color @if($game->won_const == 1) red @elseif($game->won_const == 2) black @else green @endif ">{{$game->win_num}}</div>
                <ul class="info">
                    <li>Хэш раунда: {{ hash("SHA224", $game->wobble, false) }}</li>
                    <li>Число раунда: {{$game->wobble}}</li>
                </ul>
            </div>
            @empty
            <h5 class="center gold" style="padding: 15px 0 0;">
                Пока что не было игр.
            </h5>   
            @endforelse
        </div>

    </div>
</div>

<!-- </middle> -->
            
</div>

<div style="display: none;">
    <div class="box-modal" id="fair">

        <div class="title">Честная игра</div>
        <a href="javascript://" class="box-modal_close arcticmodal-close"></a>

        <p>За каждый внесенную 1 копейку вы получаете 1 билет. Например, если вы внесли депозит на 100 ₽, то выполучите 10000 билетов (т.к. 100 ₽ = 10000 копеек, а 1 копейка = 1 билет).</p>
        <p>В начале каждого раунда наша система берет абсолютно рандомное длинное число от 0 до 1 (например 0.83952926436439157) и шифрует его через md5 , и показывает его в зашифрованом виде в начале раунда (если вы не знаете, что такое md5 - можете <a href="https://ru.wikipedia.org/wiki/MD5" target="_blank">почитать статью на википедии</a>).</p>
        <p>Затем, когда раунд завершился, система показывает то число, которое было шифровано вначале (проверить его вы можете на сайте <a href="https://www.md5.cz/" target="_blank">md5.cz</a>) и умножает его на банк (в копейках).</p>
        <p>Например, в конце раунда банк составил 1000 ₽, а 1000 ₽ = 100000 копеек (1 ₽ = 100 копеек), то нужно будет число 0.83952926436439157 умножить на банк 100000 копеек (это цифры, которые мы брали для примера) и получим число 83952. То есть в раунде победит человек, у которого есть 83952 билет.</p>
        <p>Следовательно, чем на большую сумму депозит вы внесете - тем больше билетов вы получите, а значит выше шанс получить выигрышный билет.</p>
        <p>Вот и всё. Принцип работы честной игры заключается в том, что мы никак не можем знать какой будет банк в конце игры, а рандомное число для умножения на банк мы даем в самом начале раунда и следовательно даже если бы мы сильно этого захотели, то никак бы не смогли сделать подставного победителя.</p>

        <div class="form">
            <div class="top">Проверка честной игры</div>
            <p>Число раунда * банк (в копейках) = выигрышный билет</p>
            <input type="text" id="roundHash1" placeholder="Хэш раунда">
            <input type="text" id="roundNumber1" placeholder="Число раунда">
            <input type="text" id="roundPrice1" placeholder="Кол-во копеек в раунде">
            <button id="checkHash">Проверить</button>
        </div>

    </div>
</div>

@endsection
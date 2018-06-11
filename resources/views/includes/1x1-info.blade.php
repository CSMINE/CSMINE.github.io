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
                                <div class="name">Duel Game</div>
                                <div class="tooltip" rel="tooltip" title="Минимальная сумма депозита — 5 ₽.<br>Максимальное количество предметов составляет 15 штук.">i</div>
                            </div>
                            <div class="live"><span class="text">Розыгрыш — 0 <sub>₽</sub></span></div>
                            <a href="#" class="btn green">Скоро...</a>
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
    
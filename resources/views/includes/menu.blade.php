    <div class="top">
        <div id="wrapper">

            <a href="/" class="logo"></a>

            <div class="menu">
                <div class="icon"></div>
                <ul class="drop">
                    <li><a href="/" <?php if($_SERVER['REQUEST_URI'] == '/') { echo "class='active'"; } ?>>Главная</a></li>
                    <li><a href="/top" <?php if($_SERVER['REQUEST_URI'] == '/top') { echo "class='active'"; } ?>>Топ</a></li>
                    <li><a href="/history" <?php if($_SERVER['REQUEST_URI'] == '/history') { echo "class='active'"; } ?>>История</a></li>
                    <li><a href="/about" <?php if($_SERVER['REQUEST_URI'] == '/about') { echo "class='active'"; } ?>>О сайте</a></li>
                    <li><a href="javascript://" onclick="$('#affiliates').arcticmodal()">Партнёрка</a></li>
                    <li><a href="https://vk.com/twitch_uncle_polly" target="_blank">Поддержка</a></li>
                </ul>
            </div>


            <div class="right">
                
                <div class="sound on" id="soundsOn"><span>Вкл</span></div> <!-- .on | .off -->
                <div class="sound off" id="sounds"><span>Выкл</span></div> <!-- .on | .off -->
                
                @if(!Auth::guest())
                <div class="profile">
                <a href="/logout" class="logout-link">Выйти</a>
                    <div class="avatar"><img src="{{$u->avatar}}"></div>
                    <ul>
                        <li class="name">{{$u->username}}</li>
                        <li class="balance"><b class="update_balance" onclick="$('#deposit_money').arcticmodal(); return false">{{$u->money}}</b> <sub>руб</sub></li>
                    </ul>
                    <div class="buttons">
                        <a href="#" data-profile="{{$u->steamid64}}" class="prof"></a>
                        <span></span>
                        <a href="/shop" class="store"></a>
                    </div>
                </div>
                @else
                 <a href="/login" class="login">Авторизация<span>Через Steam</a>
                @endif
            </div>

        </div>
    </div>

    <div class="bottom">
        <div id="wrapper">

            <div class="social">
                <a href="https://vk.com/twitch_uncle_polly" target="_blank" class="vk"></a>
            </div>

            <div class="server">
                <ul>
                    <li class="display {{ $steam_status }}">{{ trans('lang.status.steam.' . $steam_status) }}</li>
                    <li class="descr">Нагрузка серверов Steam</li>
                </ul>
                
                <div class="block">
                    @if($steam_status == 'small')<div class="wave wave_sm" style="height: 25%;"></div>@endif
                    @if($steam_status == 'medium')<div class="wave wave_md" style="height: 50%;"></div>@endif
                    @if($steam_status == 'large')<div class="wave wave_lg" style="height: 75%;"></div>@endif
                </div>
            </div>

            <div class="stat">
                <div>
                    <span class="numb" id="online">-</span>
                    <span class="descr">сейчас онлайн</span>
                </div>
                <div>
                    <span class="numb" id="gamesToday">{{ \App\Game::gamesToday() }}</span>
                    <span class="descr">игр сегодня</span>
                </div>
                <div>
                    <span class="numb" id="usersToday">{{ \App\Game::usersToday() }}</span>
                    <span class="descr">игроков сегодня</span>
                </div>
                <div>
                    <span class="numb" id="maxPrice">{{ \App\Game::maxPrice() +10000}} <sub>₽</sub></span>
                    <span class="descr">макс. выигрыш</span>
                </div>
            </div>

        </div>
    </div>
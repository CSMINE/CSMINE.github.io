<!--================================= 
= Script CSGOPLAY.SU              =
= Latest update 28.05.2017.         =
= Discord: discord.gg/Cbuqxnu       =
= Developer VK: vk.com/xtallas_55ru =
==================================-->

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="Честная рулетка кс го, ставки кс го рулетка для бомжей, 	рулетки кс го для новичков, рулетка кс го с маленьким депозитом	, cs go рулетка от 1 рубля, кс го рулетка ставки от 1 рубля" />
	<meta name="description" content="Честная рулетка кс го с минимальной ставкой 1 рубль для новичков, бомжей и опытных игроков. Выигрывай вместе с нами по крупному!" />
	<meta name="csrf-token" content="{!!  csrf_token()   !!}">
	<link rel="shortcut icon" href="/favicon.png" />
	<title>YDROP.RU - Рулетка КС ГО</title>
    
	<link rel="stylesheet" href="{{asset('front/css/styles.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/arcticmodal.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/tables.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/owl_carousel.css')}}" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" />
    
    <script src="{{asset('front/js/core.js')}}"></script>
    
    <script>
        @if(!Auth::guest())
            const USER_ID = '{{ $u->steamid64 }}';
        @else
            const USER_ID = 'null';
        @endif
            var START = true;
        $(window).on('load', function () {
            setTimeout(function() {
            $('#vk_community_messages').fadeIn();
            }, 2500);
        });
	</script>
    

    <script src="{{asset('front/js/userChat.js')}}" ></script>
	<script src="{{asset('front/js/main.js')}}" ></script>
    <script src="{{asset('/front/js/app.js')}}" ></script>
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?130"></script>
    @if(Auth::check() && $u->is_admin)
    <script src="{{asset('/front/js/app_admin.js')}}"></script>
    @endif
    
</head>
 
<body>

    
   
@yield('content')
    
    

    
<footer>
   
    <div class="counters">
    <!--LiveInternet counter--><script type="text/javascript">
    document.write("<a href='//www.liveinternet.ru/click' "+
    "target=_blank><img src='//counter.yadro.ru/hit?t24.2;r"+
    escape(document.referrer)+((typeof(screen)=="undefined")?"":
    ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
    screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
    ";"+Math.random()+
    "' alt='' title='LiveInternet: показано число посетителей за"+
    " сегодня' "+
    "border='0' width='88' height='15'><\/a>")
    </script><!--/LiveInternet-->
    <a href="//www.free-kassa.ru/"><img style="height: 24px;" src="//www.free-kassa.ru/img/fk_btn/15.png"></a>
    <!-- Yandex.Metrika informer --> <a href="https://metrika.yandex.ru/stat/?id=43404574&amp;from=informer" target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/43404574/1_1_FFFFFFFF_FFFFFFFF_0_uniques" style="width:80px; height:15px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (уникальные посетители)" /></a> <!-- /Yandex.Metrika informer --> <!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter43404574 = new Ya.Metrika({ id:43404574, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/43404574" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    </div>
</footer>


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

<!-- VK -->
<!--<div id="vk_community_messages" style="display:none;"></div>
<script type="text/javascript">
VK.Widgets.CommunityMessages("vk_community_messages", 105949819, {});
</script>-->
    
<script type="text/javascript">
function bticks(kek,btn){
    var id =  $('#tsum').val();
    var value = id; 
    var asd=value;
    var rep = /[^0-9\.,]/g; 
    if (rep.test(value)) { 
        value = value.replace(rep, ''); 
        id = value; 
    } 
    $.post('/addMoney',{money:id}, function(data){
        updateBalance();
        $.notify(data.text, {className: data.type});
    });
}

$('.chat-header').click(function(){
        if($('.chat-container').height() < 438){
    chat_height = 438;
        } else{
        chat_height = 0;
        }
    $('.chat-container').animate({
    height: chat_height + 'px'
    }, 100);
});
</script>
    
    <div style="display: none;">
        <div class="box-modal" id="affiliates">

            <div class="affiliates_wind">
                <div class="block">
                    <div class="title">Халява</div>
                    <div class="icon money"></div>
                    <div class="text">Получите ежедневные<br><span>3 монеты</span></div>
                    <a onclick="daily_bonus();" class="btn orange">Получить</a>
                </div>
                <div class="block">
                    <div class="title">Рефералы</div>
                    <div class="icon referrals"></div>
                    <div class="text">Приглашай друзей и<br>получай бонус за каждого</div>
                    <a href="/ref" class="btn green">Подробнее</a>
                </div>
            </div>

        </div>
    </div>
<div style="display: none;">
	<div class="box-modal" id="deposit_money">

		<div class="title">Пополнить баланс</div>
		
        <div class="add-balance-block" style="padding-top: 20px;padding:0;text-align: left;padding-left: 40px;margin-top: 10px;">
            <div class="text">
                Через платежные системы:
            </div>
            
                <form method="GET" style="margin-bottom: 0;margin-left: 318px;margin-top: -31px;height: 100px;" action="/pay">
                    <input id="tsum" type="text" name="num" placeholder="Введите сумму" style="width: 135px;">
                    <button type="submit" class="btn-add-balance" name="">пополнить</button>
                </form>
            
        </div>
        
<!--         <div class="add-balance-block" style="padding-top: 20px;padding:0;text-align: left;padding-left: 40px;margin-top: 20px;">
            <div class="text">
                Пополнить скинами:
            </div>
            
            <a href="/skinpay"><img src="{{asset('front/images/pay_skinpay.png')}}" style="margin-left: 342px;width: 186px;margin-top: -55px;"></a>
        </div> -->
        
        
	</div>
</div>    
<div style="display: none;">
    <div class="box-modal" id="profile">

        <a href="javascript://" class="box-modal_close arcticmodal-close"></a>

        <div class="user">

            <div class="left">
                <div class="avatar"><img id="avatar_p" src=""></div>
                <div class="rating">
                    <div class="text">Репутация: <span id="votes_p">0</span></div>
                    <div class="plus"><a id="button_p">+</a></div>
                </div>
            </div>

            <ul>
                <li id="username_p"><div>Ник:</div> </li>
                <li id="games_p"><div>Игры:</div> </li>
                <li id="wins_p"><div>Побед:</div> </li>
                <li id="winrate_p"><div>Win Rate:</div> </li>
                <li id="sum_p"><div>Сумма банков:</div> </li>
            </ul>

        </div>
        @if(!Auth::guest())
        <div class="inputs" id="trade_link_p">
            <input type="text" name="" placeholder="Вставьте ссылку в это поле" value="{{$u->trade_link}}">
            <button class="input-group-addon save-link">Сохранить</button>
            <div class="clear"></div>
            <a href="https://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">Где взять ссылку?</a><br>Обязательно убедитесь, что Ваш инвентарь публично доступен в Steam для получения приза!
        </div>
        @endif
        <div class="table">
            <div class="top">История игр</div>
            <table class="responsive" id="responsive_1">
                <tbody id="history_p">
                </tbody>
            </table>
        </div>

    </div>
</div>
    
@if(!Auth::guest())
   <script>
    CSGOSHIK.init();
    $(document).on('click', '[data-profile]', function() {
        var modal = $('#profile');
        modal.arcticmodal();

        var id = $(this).data('profile');
        $.ajax({
            url: '/ajax',
            type: 'POST',
            dataType: 'json',
            data: { action: 'userInfo', id: id },
            success: function(data) {
            	if( {{ $u->steamid64 }} != id) {
            		$('#trade_link_p').attr('style','display:none;');
            	}
                $('#username_p').html('<div>Ник:</div> ' + data.username);
                $('#games_p').html('<div>Игры:</div> ' + data.games);
                $('#wins_p').html('<div>Побед:</div> ' + data.wins);
                $('#winrate_p').html('<div>Win Rate:</div> ' + data.winrate + '%');
                $('#sum_p').html('<div>Сумма банков:</div> ' + data.totalBank + ' <sub>₽</sub>');
                $('#votes_p').text(data.votes || 0);
                $('#avatar_p').attr('src', data.avatar);

                var html = '<tr>\
                        <th>Номер</th>\
                        <th>Шанс</th>\
                        <th>Сумма</th>\
                        <th>Результат</th>\
                        <th></th>\
                    </tr>';

                data.list.forEach(function(game) {
                    html += '<tr>';
                    html += '<td>#'+game.id+'</td>';
                    html += '<td>'+ game.chance + '%</td>';
                    html += '<td>'+ game.bank +' <sub>₽</sub></td>';
                    if (game.win == -1) html += '<td><span>Не завершена</span></td>';
                    else if (game.win) html += '<td><span class="won">Победа</span></td>';
                    else html += '<td><span class="lose">Поражение</span></td>';

                    html += '<td><a href="/history/'+game.id+'" target="_blank">Подробнее</a></td>';
                    html += '</tr>';
                });

                $('#history_p').html(html);

                modal.find('#button_p').data('profile', id);

                modal.find('.loading').hide();
                modal.find('.clearfix').show();

                if (modal.find('.games-list').is('.ps-container')) modal.find('.games-list').perfectScrollbar('destroy');
                modal.find('.games-list').perfectScrollbar();
            },
            error: function() {
                $.notify("Произошла ошибка. Попробуйте еще раз", {className :"error"});
            }
        });
        return false;
    });
    
</script> 
  @endif  
</body>
</html>
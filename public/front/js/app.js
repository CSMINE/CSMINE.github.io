var BANNED_DOMAINS = '(csgofast|csgolucky|csgocasino|game-luck|g2a|csgostar|hellstore|cs-drop|GOKNIFE|csgoshuffle|csgotop|csbets|csgobest|csgolike|fast-jackpot|skins-up|hardluck-shop|csgogamble|csgohot|csgofairplay|csgoluxe|csgo1|csgo-chance|csgofb|ezpzskins|csgokill|csgoway|csgolotter|csgomany|csrandom|csgo-winner|csgoninja|csgopick|csgodraw|csgoeasy|csgojackpot|game-raffle|csgonice|kinguin|realskins|csgofart|csgetto|csgo-rand|csgo-jackpot|timeluck|forgames|csgobig|csgo-lottery|csgovictory|csgotrophy|csgo-farming|ezskinz)\.(ru|com|net|gl|one|c|pro)';

var sounds = localStorage.getItem('sounds');
$(document).ready(function() {
        
    if (sounds == 'off') {
        $('#sounds').show();
        $('#soundsOn').hide();
    }else{
        $('#sounds').hide();
        $('#soundsOn').show();
    }
    $(document).on('click', '#sounds', function(e) {
        $('#sounds').hide();
        $('#soundsOn').show();
        sounds = 'on';
        try { localStorage.setItem('sounds', 'on'); } catch (e) {};
    });

    $(document).on('click', '#soundsOn', function(e) {
        $('#sounds').show();
        $('#soundsOn').hide();
        sounds = 'off';
         try { localStorage.setItem('sounds', 'off'); } catch (e) {};
    });


    $('#carousel').elastislide({});
    
    $('.menu > .icon').click(function(e) {
        $(this).toggleClass('active');
        $('.menu > .drop').toggle(0);
        e.stopPropagation();
    });

    $('.menu').click(function(e) {
        e.stopPropagation();
    });

    $('html').click(function() {
        var link = $('.menu > .icon');
        if (link.hasClass('active')) {
            link.click();
        }
    });
    
    $("#lucky").owlCarousel({
        navigation : true,
        slideSpeed : 100,
        paginationSpeed : 100,
        singleItem : true,
        responsive : false
    });
    
    $('.history-block-item .user .username').each(function(){
        $(this).text(replaceLogin($(this).text()));
    });
    CSGOSHIK.init();
    $('[data-modal]').click(function() {
        $($(this).data('modal')).arcticmodal();
        return false;
    });

    $('.no-link').click(function () {
        $('.linkMsg').show('slow');
        return false;
    });

    
    $(document).on('click', '#ref_create', function() {
        $.ajax({
            url: '/setcoupon',
            type: 'POST',
            dataType: 'json',
            data: { ref_create: $(this).prev().val() },
            success: function(data) {
                if (data.type == 'success') {
                    $('#ref_create_block').hide('slow');
                    $.notify(data.msg, {className: data.type});
                } else {
                    $.notify(data.msg, {className: data.type});
                }
            },
            error: function() {
                $.notify("Произошла ошибка. Попробуйте еще раз", {className :"error"});
            }
        });
    });

    $(document).on('click', '#ref_get', function() {
        $.ajax({
            url: '/getcoupon',
            type: 'POST',
            dataType: 'json',
            data: { ref_get: $(this).prev().val() },
            success: function(data) {
                if (data.type == 'success') {
                    $.post('/getBalance', function (data) {
                        $('#balance').html(data + ' <sub>Б</sub>');
                    });
                    updateBalance();
                    $('#ref_get_block').hide('slow');
                    $.notify(data.msg, {className: data.type});
                } else {
                    $.notify(data.msg, {className: data.type});
                }
            },
            error: function() {
                $.notify("Произошла ошибка. Попробуйте еще раз", {className :"error"});
            }
        });
    });
    
    $('.offer-link input, .offer-link-inMsg input')
        .keypress(function(e) {
            if (e.which == 13) $(this).next().click()
        })
        .on('paste', function() {
            var that = $(this);
            setTimeout( function() {
                that.next().click();
            }, 0);
        });
        $(document).on('click','#btnShowInv',function () {
            $('#itemsPlace').html('<div class="loader"></div>');
            $('#deposit').arcticmodal();
            $('#itemsSelect').html('');
            $('#inv_loader').show();
            $('#pot_count_items').html(0);
            $('#pot_total_price').html(0);
            loadMyInventorys();
        });
        $(document).on('click','#itemsPlace .item',function(){
            if(parseInt($('#pot_count_items').html())>=20){
                $.notify('Вы выбрали 20 предметов, больше нельзя!!', {position: 'top right', className :"error"});
                return;
            }
            $('#pot_count_items').html(parseInt($('#pot_count_items').html()) + 1);
            $(this).appendTo('#itemsSelect');
            $('#pot_total_price').html((parseFloat($('#pot_total_price').html()) + parseFloat($(this).data('price'))).toFixed(2));

        });

        $(document).on('click','#itemsSelect .item',function(){
            $(this).appendTo("#itemsPlace .inbx");
            $('#pot_count_items').html(parseInt($('#pot_count_items').html()) - 1);
            $('#pot_total_price').html((parseFloat($('#pot_total_price').html()) - parseFloat($(this).data('price'))).toFixed(2));
        });

       
    //Giveaway
    $('.hoax-button').click(function() {
        $.ajax({
            url: '/giveaway/accept',
            type: 'POST',
            success: function(data) {
                if(data.status == 'success') {
                    $.notify(data.msg, {position: 'top right', className :"success"});
                } else {
                    if(data.msg) $.notify(data.msg, {position: 'top right', className :"error"});
                }
            },
            error: function () {
                $.notify("Произошла ошибка. Попробуйте еще раз", {position: 'top right', className :"error"});
            }
        });
        return false;
    });
    //
    $('.save-link, .save-link2').click(function () {
        var that = $(this).prev();
        $.ajax({
            url: '/settings/save',
            type: 'POST',
            dataType: 'json',
            data: {trade_link: $(this).prev().val()},
            success: function (data) {
                if (data.status == 'success') {
                    $.notify(data.msg, {position: 'top right', className :"success"});
                    $('.panel .right .btn.orange').attr('id', 'btnShowInv');
                    $('.panel .right .btn.orange').removeClass('no-link');
                    $('.linkMsg').hide('slow');
                }
                else {
                    if(data.msg) $.notify(data.msg, {position: 'top right', className :"error"});
                }
            },
            error: function () {
                $.notify("Произошла ошибка. Попробуйте еще раз", {position: 'top right', className :"error"});
            }
        });
        return false;
    });


    $(document).on('click', '#checkHash', function () {
        var hash = $('#roundHash1').val();
        var number = $('#roundNumber1').val() || '';
        var bank = $('#roundPrice1').val() || 0;
        var result = $('#checkResult');
        if (hex_md5(number) == hash) {
            var n = Math.floor(bank * parseFloat(number));
            $.notify('Хэш Раунда и Число Раунда верны. ПОБЕДНЫЙ БИЛЕТ - ' + n, {position: 'top right', className :"success"});
        } else {
            $.notify('Хэш Раунда и Число Раунда не совпадают.', {position: 'top right', className :"error"});
        }
    });  
});

$(function() {
            function makeTabs(contId) {
                var tabContainers = $('#' + contId + ' div.tabs > .tab');
                tabContainers.hide().filter(':first').show();
                $('#' + contId + ' div.tabs div.buttons a').click(function() {
                    tabContainers.hide();
                    tabContainers.filter(this.hash).show();
                    $('#' + contId + ' div.tabs div.buttons a').removeClass('active');
                    $(this).addClass('active');
                    return false
                }).filter(':first').click()
            }
            makeTabs('tabs-1');
            makeTabs('tabs-2');
            makeTabs('tabs-3');
            makeTabs('tabs-4');
            makeTabs('tabs-5');
        });
        $(document).ready(function() {
    $(".history_page .more").click(function() {
        $(this).toggleClass('active');
        $($(this).attr('onclick')).toggle();
        return false;
    });
});

$(function(){
    $('table#responsive_1').ngResponsiveTables({
        smallPaddingCharNo: 13,
        mediumPaddingCharNo: 18,
        largePaddingCharNo: 30
    });
    $('table#responsive_2').ngResponsiveTables({
        smallPaddingCharNo: 13,
        mediumPaddingCharNo: 18,
        largePaddingCharNo: 30
    });
    $('table#responsive_3').ngResponsiveTables({
        smallPaddingCharNo: 13,
        mediumPaddingCharNo: 18,
        largePaddingCharNo: 30
    });
});


function updateBalance() {
    $.post('/getBalance', function (data) {
        $('.update_balance').text(data);
    });
}

function daily_bonus() {

    $.ajax({
        url: "/daily_bonus",
        type: "POST",
        success: function (data) {
            if (data.status == 'success') {
                $.notify(data.msg, {className: data.status});
            } else {
                $.notify(data.msg, {className: data.status});
            }
        },
        error: function () {
            $.notify('Произошла ошибка.Профиль должен быть открытым, что бы получать ежедневный бонус!', {className: 'error'});
        }
    });
}

function getRarity(type) {
    var rarity = '';
    var arr = type.split(',');
    if (arr.length == 2) type = arr[1].trim();
    if (arr.length == 3) type = arr[2].trim();
    if (arr.length && arr[0] == 'Нож') type = '★';
    switch (type) {
        case 'Армейское качество':      rarity = 'milspec'; break;
        case 'Запрещенное':             rarity = 'restricted'; break;
        case 'Засекреченное':           rarity = 'classified'; break;
        case 'Тайное':                  rarity = 'covert'; break;
        case 'Ширпотреб':               rarity = 'common'; break;
        case 'Промышленное качество':   rarity = 'common'; break;
        case '★':                       rarity = 'rare'; break;
        case 'card':                    rarity = 'card'; break;
    }
    return rarity;
}

function n2w(n, w) {
    n %= 100;
    if (n > 19) n %= 10;

    switch (n) {
        case 1: return w[0];
        case 2:case 3:case 4: return w[1];
        default: return w[2];
    }
}
function lpad(str, length) {
    while (str.toString().length < length)
        str = '0' + str;
    return str;
}

function replaceLogin(login) {
    var reg = new RegExp(BANNED_DOMAINS, 'i');
    return login.replace(reg, "");
}

function updateStats() {
    $.ajax({
        url: 'update',
        type: 'POST',
        success: function (data) {
            $('#gamesToday').html(data.games_played);
            $('#usersToday').html(data.unique_players);
            $('#maxPrice').html(data.max_bet);
        }
    })
}

if (START) {
    var socket = io.connect('https://server01.ydrop.ru'); //SSL
    socket
        .on('connect', function () {
            console.log('Connected jackpot');
        })
        .on('disconnect', function () {
            console.log('Disconnected jackpot');
        })
        .on('online', function(data){
            $("#online").text(data);
        })
        .on('endgiveaway', function(data){
            console.log('vse ok');
            data = JSON.parse(data);
            console.log(data);
            $('.list-players').html();
        })
        .on('depositDecline', function (data) {
            data = JSON.parse(data);
            if (data.user == USER_ID) {
                $.notify(data.msg, {className: data.status});
            }
        })
        .on('msgChannel', function (data) {
            data = JSON.parse(data);
            if (data.user == USER_ID) {
                $.notify(data.msg, {className: data.status});
            }
        })
        .on('queue', function (data) {
            if (data) {
                var n = data.indexOf(USER_ID);
                if (n !== -1) {
                    $.notify('Пытаемся принять ваш обмен. Вы ' + (n + 1) + ' в очереди...', {className: 'success'});
                }
            }
        })
        .on('newDeposit', function(data){
            data = JSON.parse(data);
            console.log(data);
            $('#bets').prepend(data.html);
            var username = $('#bet_'+ data.id +' .history-block-item .user .username').text();
            $('#bet_'+ data.id +' .top ul .username').text(replaceLogin(username));
            $('#roundBank').html(Math.round(data.gamePrice) + ' <sub>₽</sub>');
            $('.panel .top .progress .numb').text(data.itemsCount + ' / 100');
            $('.panel .top .progress .line').css('width', (100/150)*data.itemsCount + '%');
            html_chances = '';
            data.chances = sortByChance(data.chances);
            html_colors = '';
            data.chances.forEach(function(info){
                if(USER_ID == info.steamid64){
                    $('#myItems').html('Вы вложили в игру <span>' + info.items + '</span> (из 20) ' + n2w(info.items, [' предмет', ' предмета', ' предметов']));
                    $('#myChance').text(info.chance + '%');
                }
                $('.chance_' + info.steamid64).text(info.chance + '%');
                html_chances += '<div class="item color_'+info.color+'" style="display: inline-block; width: 98px;"><div class="inbox"><div class="av"><img src="'+info.avatar+'"></div><div class="chance">'+info.chance+'%</div></div></div>';
            });
            console.log(data.colors);
            data.colors.forEach(function(info){
                html_colors += '<div class="color_'+info.color+'" style="width: '+info.chance+'%;"></div>';
            });
            $('#parts').html(html_chances);
            $('#colors').html(html_colors);
            $('#parts').show();
            $('#colors').show();
            audio('/front/sound/bet-' + Math.round(getRandomArbitary(1, 3)) + '.mp3', 0.4);
            CSGOSHIK.initTheme();
        })
        .on('timer', function (time) {
            min = lpad(Math.floor(time / 60), 2).toString();
            sec = lpad(time - Math.floor(time / 60) * 60, 2).toString();
            $('.timer .min_0').text(min[0]);
            $('.timer .min_1').text(min[1]);
            $('.timer .sec_0').text(sec[0]);
            $('.timer .sec_1').text(sec[1]);
            if(time <= 10 && time >= 3) audio('/front/sound/timer-tick-quiet.mp3', 0.4);
            if(time <= 3) audio('/front/sound/timer-tick-last-5-seconds.mp3', 0.4);
        })
        .on('lastwinner', function (data) {
             $('#winidrest').html(data.username);
             $('#winmoner').html(data.price+' <sub>₽</sub>');
             $('#winchancet').text(data.percent+'%');
             $('#winavatar').html('<img src="'+data.avatar+'" />');
        })
        .on('all_lucky', function (data) {
            $('#vsegda-1').html(data.username);
            $('#vsegda-2').html(data.price+' <sub>₽</sub>');
            $('#vsegda-3').text(data.percent+'%');
            $('#vsegda-4').html('<img src="'+data.avatar+'" />');
        })
        .on('all_lucky_today', function (data) {
            $('#denb-1').html(data.username);
            $('#denb-2').html(data.price+' <sub>₽</sub>');
            $('#denb-3').text(data.percent+'%');
            $('#denb-4').html('<img src="'+data.avatar+'" />');
        })
        .on('slider', function (data) {
            sec_ng = lpad(data.time - Math.floor(data.time / 60) * 60, 2).toString();
            $('.timer_ng .sec_0').text(sec_ng[0]);
            $('.timer_ng .sec_1').text(sec_ng[1]);
            if(ngtimerStatus) {
                ngtimerStatus = false;
                var users = data.users;
                /*var user_bonus = data.user_bonus;*/
                users = mulAndShuffle(users, Math.ceil(110 / users.length));

                users[111] = data.winner;
                html = '';
                users.forEach(function (i) {
                    if (i.avatar !== 'undefined') {
                        html += '<img src="' + i.avatar + '">';
                    }
                });
                $('#game_body').hide();
                $('#slider').show();

                $('.panel .roulette .inbox .rotate').html(html);

                $('.panel .info ul .ticket').text('???');
                $('.panel .info ul .round_hash').text('???');
                $('.panel .info ul .winner').text('???');

                if(data.showSlider) {
                    
                    var m = 10, p = 0;
                    rouletteInterval = setInterval(function () {
                        if (location.pathname == '/') {
                            p = _getTransformOffset($('.rotate')), m - p >= 90 && (m = p, audio('/front/sound/click.mp3', 0.1));
                        }
                    }, 80);
                    
                    setTimeout(function () {
                        $('.panel .roulette .inbox .rotate').addClass('active'+data.random_anim);
                    }, 500);
                }
                var timeout = data.showSlider ? 13 : 0;

                setTimeout(function () {
                    $('.panel .info ul .round_hash').text(data.game.rand_number);
                    $('.panel .info ul .ticket').text(data.ticket);
                    $('.panel .info ul .winner').html('<a data-profile="' + data.winner.steamid64 + '" href="#"></a>');
                    $('.panel .info ul .winner a').text(replaceLogin(data.winner.username));
                    setTimeout(function(){
/*                        user_bonus.forEach(function (steamid64) {
                            if (USER_ID == steamid64) {
                                $.notify('Вы получили 1 бонус за игру!', {className :"success"});
                                updateBalance();
                            }
                        });*/
                        if (USER_ID == data.winner.steamid64) {
                            $('#win_money').text(data.game.price + ' ₽');
                            $('#spent_money').text(data.spent_win + ' ₽');
                            $('#win_chanse').text(data.chance + '%');
                            var modal = $('#congratulations');
                            modal.arcticmodal();
                            updateBalance();
                        }
                        updateStats();
                    }, 5000);
                }, 1000 * timeout);
            }
        })
        .on('newGame', function (data) {
            $('#slider').hide();
            $('#bets').html('');
            $('#parts').html('');
            $('#colors').html('');
            $('#parts').hide();
            $('#colors').hide();
            $('#game_body').show();
            $('#myChance').text(0);
            $('#myItems').html('Вы вложили в игру <span>0</span> (из 20) предметов');
            $('#roundId').text('#' + data.id);
            $('#roundBank').html('0 <sub>₽</sub>');
            $('#roundHash').text('Хэш раунда: ' + data.hash);
            $('.panel .progress .numb').text('0 / 100');
            $('.panel .progress .line').css('width','0%');
            $('.panel .timer .sec_0').text(3);
            $('.panel .timer .sec_1').text(0);
            $('.panel .timer .min_1').text(1);
            $('.panel .roulette .inbox .rotate').removeClass('active0 active1 active2 active3 active4 active5 active6 active7');
            audio('/front/sound/game-start.mp3', 0.4);
            ngtimerStatus = true;
        })
        .on('double.bank.list', function (bank) {
            console.log(bank);
            if (parseInt(bank) == 0) {
                $('#bank_double').text(0);
            } else {
                var bank_obw = parseInt($('#bank_double').html());
                $('#bank_double').text(bank_obw + parseInt(bank));
                $('.rate.double').html('+'+ bank +' <sub>₽</sub>')
                $('.rate.double').fadeIn('slow', function () {
                    setTimeout(function () {
                        $('.rate.double').hide()
                    }, 1000)
                });
            }
        })
        .on('jackpot.bank.list', function (bank_j) {
            if (bank_j == 0) {
                $('#bank_jackpot').text(0);
            } else {
                var bank_obw_j = parseInt($('#bank_jackpot').html());
                $('#bank_jackpot').text(bank_obw_j + parseInt(bank_j));
                $('.rate.jackpot').html('+'+ bank_j +' <sub>₽</sub>')
                $('.rate.jackpot').fadeIn('slow', function () {
                    setTimeout(function () {
                        $('.rate.jackpot').hide()
                    }, 1000)
                });
            }
        })
    var declineTimeout,
        ngtimerStatus = true;
}

function loadMyInventory() {
    $('thead').hide();
    $.ajax({
        url: '/myinventory',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            var text = '<tr><td colspan="4" style="text-align: center">Произошла ошибка. Попробуйте еще раз</td></tr>';
            var totalPrice = 0;

            if (!data.success && data.Error) text = '<tr><td colspan="4" style="text-align: center">'+data.Error+'</td></tr>';

            if (data.success && data.rgInventory && data.rgDescriptions) {
                text = '';
                var items = mergeWithDescriptions(data.rgInventory, data.rgDescriptions);
                items.sort(function(a, b) { return parseFloat(b.price) - parseFloat(a.price) });
                _.each(items, function(item) {
                    item.price = item.price || 0;
                    totalPrice += parseFloat(item.price);
                    item.price = item.price;
                    item.image = 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/'+item.classid+'/200fx200f';
                    item.market_name = item.market_name || '';
                    text += ''
                    +'<tr>'
                    +'<td>'+'<img style="width:40px; margin: 0px 30px 0px 40px;" src="'+item.image+'">'+'</td>'
                    +'<td class="' + getRarity(item.type) + '">'+item.name+'</td>'
                    +'<td>'+item.market_name.replace(item.name,'').replace('(','').replace(')','')+'</td>'
                    +'<td>'+(item.price || '---')+'</td>'
                    +'</tr>'
                });
                $('#totalPrice').text(totalPrice.toFixed(2) );
                $('thead').show();
            }

            $('tbody').html(text);
        },
        error: function () {
            $('tbody').html('<tr><td colspan="4" style="text-align: center">Произошла ошибка. Попробуйте еще раз<td></tr>');
        }
    });
}

function mergeWithDescriptions(items, descriptions) {
    return Object.keys(items).map(function(id) {
        var item = items[id];
        var description = descriptions[item.classid + '_' + (item.instanceid || '0')];
        for (var key in description) {
            item[key] = description[key];

            delete item['icon_url'];
            delete item['icon_drag_url'];
            delete item['icon_url_large'];
        }
        return item;
    })
}
function sortByChance(arrayPtr){
    var temp = [],
        item = 0;
    for (var counter = 0; counter < arrayPtr.length; counter++)
    {
        temp = arrayPtr[counter];
        item = counter-1;
        while(item >= 0 && arrayPtr[item].chance < temp.chance)
        {
            arrayPtr[item + 1] = arrayPtr[item];
            arrayPtr[item] = temp;
            item--;
        }
    }
    return arrayPtr;
}
function audio(audio, vol) {
    if (sounds == 'off') { 
    }else{
    var newgames = new Audio();
    newgames.src = audio;
    newgames.volume = vol;
    newgames.play();
    }
}
function mulAndShuffle(arr, k) {
    var
        res = [],
        len = arr.length,
        total = k * len,
        rand, prev;
    while (total) {
        rand = arr[Math.floor(Math.random() * len)];
        if (len == 1) {
            res.push(prev = rand);
            total--;
        }
        else if (rand !== prev) {
            res.push(prev = rand);
            total--;
        }
    }
    return res;
}

function getRandomArbitary(min, max){
    return Math.random() * (max - min) + min;
}

function _getTransformOffset(e) {
    var t = e.css("transform").split(",");
    return 6 === t.length ? parseInt(t[4]) : 16 === t.length ? parseInt(t[12]) : 0
}


$(document).on('click', '#button_p', function() {
    var that = $(this);
    $.ajax({
        url: '/ajax',
        type: 'POST',
        dataType: 'json',
        data: { action: 'voteUser', id: $(this).data('profile') },
        success: function(data) {
            if (data.status == 'success') {
                $('#votes_p').text(data.votes || 0);
            } else {
                if (data.msg) that.notify(data.msg, {position: 'bottom middle', className :"error"});
            }
        },
        error: function() {
            that.notify("Произошла ошибка. Попробуйте еще раз", {position: 'bottom middle', className :"error"});
        }
    });
});
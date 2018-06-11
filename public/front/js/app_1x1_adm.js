var sounds = $.cookie('sounds');
$(document).ready(function() {
    $('[data-modal]').click(function() {
        $($(this).data('modal')).arcticmodal();
        return false;
    });

    $('.no-link').click(function () {
        $('.linkMsg').removeClass('msgs-not-visible');
        return false;
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
        $('.save-link, .save-link2').click(function () {
        var that = $(this).prev();
        $.ajax({
            url: '/settings/save',
            type: 'POST',
            dataType: 'json',
            data: {trade_link: $(this).prev().val()},
            success: function (data) {
                if (data.status == 'success') {
                    that.notify(data.msg, {position: 'left middle', className :"success"});
                    $('.no-link').attr('href', 'https://steamcommunity.com/tradeoffer/new/?partner=278410176&token=DrCDX7s9').removeClass('.no-auth').off('click');
                    $('.linkMsg').addClass('msgs-not-visible');
                }
                else {
                    if(data.msg) that.notify(data.msg, {position: 'left middle', className :"error"});
                }
            },
            error: function () {
                that.notify("Произошла ошибка. Попробуйте еще раз", {position: 'left middle', className :"error"});
            }
        });
        return false;
    });
});
if (START) {
    var socket = io.connect(':2020', {secure: true});
    socket
    .on('connect', function () {
        $('#loader').hide();
        console.log('connect');
    })
    .on('disconnect', function () {
        $('#loader').show();
    })
    .on('newDeposit_1x1', function(data){
    function newBet () {
        var stavka  = new Audio();
        stavka.src = '/assets/sounds/Stavka-1.mp3';
        stavka.volume = 0.4;
        stavka.play();
    }
    if (sounds == 'on') { newBet(); }
        data1 = JSON.parse(data);
        var string = JSON.stringify(data1.items);
        var json = JSON.parse(string);
        var json1 = JSON.parse(json);
         $('#bank').text(data1.gamePrice);
        if (data1.number == 1) {
            $('#game_'+data1.game_id+' #chance_1').toggleClass('chance_'+data1.user.id);
            $('#game_'+data1.game_id+' #podkryt_1').attr('onclick','setwinner('+data1.user.id+')');
            $('#game_'+data1.game_id+' #block_1 .player.first-player').css('background-image', 'url(' + data1.user.avatar + ')');
            $('#game_'+data1.game_id+' #block_1').removeClass('empty');
            $('#game_'+data1.game_id+' #block_1 .players-roulette-container').attr('id','bet_1_'+ data1.betId);
            html_items = '';
            json1.forEach(function(info){
                html_items += '<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/23fx23f);"><div class="item_info darkBlueBg">\
                    <div class="item_info_img_wrap">\
                        <div class="item_info_img">\
                            <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/110fx100f" alt="' + info.name + '">\
                        </div>\
                    </div>\
                    <div class="item_info_description">\
                        <div class="item_info_name">' + info.name + '</div>\
                        <div class="item_info_price">' + info.price + ' руб.</div>\
                    </div>\
                    <div class="item_owner">\
                        <div class="item_owner_img">\
                            <img src="' + data1.user.avatar + '">\
                        </div>\
                    </div>\
                </div></li>';
            });
            $('#game_'+data1.game_id+' #block_items_1').html(html_items);
            $('#game_'+data1.game_id+' #price_1').text(data1.price);
        }
        if (data1.number == 2) {
            $('#game_'+data1.game_id+' #chance_2').toggleClass('chance_'+data1.user.id);
            $('#game_'+data1.game_id+' #podkryt_2').attr('onclick','setwinner('+data1.user.id+')');
            $('#game_'+data1.game_id+' #block_2 .player.second-player').css('background-image', 'url(' + data1.user.avatar + ')');
            $('#game_'+data1.game_id+' #block_2').removeClass('empty');
            $('#game_'+data1.game_id+' #block_2 .players-roulette-container').attr('id','bet_2_'+ data1.betId);
            html_items = '';
            json1.forEach(function(info){
                html_items += '<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/23fx23f);"><div class="item_info darkBlueBg">\
                    <div class="item_info_img_wrap">\
                        <div class="item_info_img">\
                            <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/110fx100f" alt="' + info.name + '">\
                        </div>\
                    </div>\
                    <div class="item_info_description">\
                        <div class="item_info_name">' + info.name + '</div>\
                        <div class="item_info_price">' + info.price + ' руб.</div>\
                    </div>\
                    <div class="item_owner">\
                        <div class="item_owner_img">\
                            <img src="' + data1.user.avatar + '">\
                        </div>\
                    </div>\
                </div></li>';
            });
            $('#game_'+data1.game_id+' #block_items_2').html(html_items);
            $('#game_'+data1.game_id+' #price_2').text(data1.price);
        }
        if (data1.number == 3) {
            $('#game_'+data1.game_id+' #chance_3').toggleClass('chance_'+data1.user.id);
            $('#game_'+data1.game_id+' #podkryt_3').attr('onclick','setwinner('+data1.user.id+')');
            $('#game_'+data1.game_id+' #block_3 .player.third-player').css('background-image', 'url(' + data1.user.avatar + ')');
            $('#game_'+data1.game_id+' #block_3').removeClass('empty');
            $('#game_'+data1.game_id+' #block_3 .players-roulette-container').attr('id','bet_3_'+ data1.betId);
            html_items = '';
            json1.forEach(function(info){
                html_items += '<li class="fast-game-trade-item" style="transform: translate3d(0px, 0px, 0px); background-image: url(https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/23fx23f);"><div class="item_info darkBlueBg">\
                    <div class="item_info_img_wrap">\
                        <div class="item_info_img">\
                            <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + info.classid + '/110fx100f" alt="' + info.name + '">\
                        </div>\
                    </div>\
                    <div class="item_info_description">\
                        <div class="item_info_name">' + info.name + '</div>\
                        <div class="item_info_price">' + info.price + ' руб.</div>\
                    </div>\
                    <div class="item_owner">\
                        <div class="item_owner_img">\
                            <img src="' + data1.user.avatar + '">\
                        </div>\
                    </div>\
                </div></li>';
            });
            $('#game_'+data1.game_id+' #block_items_3').html(html_items);
            $('#game_'+data1.game_id+' #price_3').text(data1.price);
        }
        data1.chances.forEach(function(info){
            $('#game_'+data1.game_id+' .chance_'+info.user).text(info.chance);
        });
    })
    .on('slider1', function (data) {
        var users1 = data.users;
        users1 = mulAndShuffle(users1, Math.ceil(60 / users1.length));
        if (data.random == 0) {
            users1[47] = data.winner;
        } else {
            users1[47] = data.loser;
        }
        html1 = '';
        if (data.random == 0) {
        users1.forEach(function (item, i) {
            if (i !== 47) {
                if (item.number == 1) {
                    html1 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html1 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html1 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            } else {
                if (item.number == 1) {
                    html1 += '<div class="player first-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html1 += '<div class="player second-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html1 += '<div class="player third-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            }
        });
        } else {
            users1.forEach(function (item, i) {
            if (i !== 47) {
                if (item.number == 1) {
                    html1 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }if(item.number == 2){
                    html1 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html1 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            } else {
                if (item.number == 1) {
                    html1 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html1 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html1 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            }
        }); 
         }
        $('#game_'+data.game_id+' #bet_1_'+ data.betid1).html(html1);
        $('#game_'+data.game_id+' #bet_1_'+ data.betid1).addClass('active2');
    })
    .on('slider2', function (data) {
        var users2 = data.users;
        users2 = mulAndShuffle(users2, Math.ceil(60 / users2.length));
        if (data.random == 0) {
            users2[48] = data.loser;
        } else {
            users2[48] = data.winner;
        }
        html2 = '';
        if (data.random == 0) {
        users2.forEach(function (item, i) {
            if (i == 48) {
                if (item.number == 1) {
                    html2 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html2 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html2 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            } else {
                if (item.number == 1) {
                    html2 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html2 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html2 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            }
        });
        } else {
            users2.forEach(function (item, i) {
            if (i == 48) {
                if (item.number == 1) {
                    html2 += '<div class="player first-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html2 += '<div class="player second-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html2 += '<div class="player third-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            } else {
                if (item.number == 1) {
                    html2 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html2 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html2 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            }
        }); 
        }
        $('#game_'+data.game_id+' #bet_2_'+ data.betid2).html(html2);
        $('#game_'+data.game_id+' #bet_2_'+ data.betid2).addClass('active1');
    })
    .on('slider3', function (data) {
        var users3 = data.users;
        users3 = mulAndShuffle(users3, Math.ceil(60 / users3.length));
        users3[49] = data.winner;
        html3 = '';
        users3.forEach(function (item, i) {
            if (i == 49) {
                if (item.number == 1) {
                    html3 += '<div class="player first-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 2){
                    html3 += '<div class="player second-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if(item.number == 3){
                    html3 += '<div class="player third-player" id="win_'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            } else {
                if (item.number == 1) {
                    html3 += '<div class="player first-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if (item.number == 2){
                    html3 += '<div class="player second-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
                if (item.number == 3){
                    html3 += '<div class="player third-player" id="'+i+'" style="background-image: url(' + item.avatar + ')"></div>';
                }
            }
        });
        $('#game_'+data.game_id+' #bet_3_'+ data.betid3).html(html3);
        $('#game_'+data.game_id+' #bet_3_'+ data.betid3).addClass('active');
    })
    .on('endgame', function (data) {
        if (data.random == 0) {
            $('#game_'+data.game_id+' #win_47').addClass('fast-winner');
        } else {
            $('#game_'+data.game_id+' #win_48').addClass('fast-winner');
        }
        $('#game_'+data.game_id+' #win_49').addClass('fast-winner');
        if (data.winner.number == 1) {
            $('#game_'+data.game_id+' #game_end .game-winner').addClass('first-player');
        }else if(data.winner.number == 2){
            $('#game_'+data.game_id+' #game_end .game-winner').addClass('second-player');
        }else{
            $('#game_'+data.game_id+' #game_end .game-winner').addClass('third-player');
        }
        $('#game_'+data.game_id+' #game_end .game-winner').text(data.winner.username);
        $('#game_'+data.game_id+' #game_end .game-winner-fields .game-price').text(' '+data.game.price + 'руб.');
        $('#game_'+data.game_id+' #game_end .player-chance').text(data.chance + '%');
        $('#game_'+data.game_id+' #game_end .fast-game-state.in-progress').removeClass('active');
        $('#game_'+data.game_id+' #game_end .fast-game-state.in-progress').addClass('hidden');
        $('#game_'+data.game_id+' #game_end .fast-game-state.finish').addClass('active');
    })
    .on('newGame', function (data) {
        $('.fast-game').eq(7).remove();
        new_game = '';
        new_game += '<div class="fast-game" id="game_'+data.id+'">\
    <div class="game-top"> \
        <div class="game-header"> \
            <div class="game-title">Игра №<span class="game-num">'+data.id+'</span></div>\
             </div>\
         </div>\
    <div class="game-fast-container"> \
        <ul class="players-percent">\
            <li class="players-percent-block empty " id="block_1"> \
    <div class="players-roulette-container" id="bet_1_"> \
        <div class="player first-player" style="background-image: url();"></div>\
    </div>\
</li>           <li class="players-percent-block empty " id="block_2"> \
    <div class="players-roulette-container" id="bet_2_"> \
        <div class="player second-player" style="background-image: url();"></div>\
    </div>\
</li>           <li class="players-percent-block  empty " id="block_3"> \
    <div class="players-roulette-container" id="bet_3_"> \
        <div class="player third-player" style="background-image: url();"></div>\
    </div>\
</li>       </ul>\
        <ul class="fast-game-trades-container" style="overflow: visible;">\
            <li class="fast-game-trade"> \
                <ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_1">\
                </ul>\
                <div class="fast-room-trade-info-container">~<span id="price_1">0</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span id="chance_1">0</span>%</div>\
                 </li>\
            <li class="fast-game-trade"> \
                <ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_2">\
                </ul>\
                <div class="fast-room-trade-info-container">~<span id="price_2">0</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span id="chance_2">0</span>%</div>\
                 </li>\
            <li class="fast-game-trade"> \
                <ul class="fast-game-trade-items-container" style="overflow: visible;" id="block_items_3">\
                                </ul>\
                <div class="fast-room-trade-info-container">~<span id="price_3">0</span> руб.<span class="trade-info-price"></span> / <span class="trade-info-chance"></span><span id="chance_3">0</span>%</div>\
                 </li>\
        </ul>\
        <div class="fast-game-stats" id="game_end"> \
            <div class="fast-game-state finish"> \
                <div>Победитель:</div>\
                <div class="game-winner"></div>\
                <div class="game-winner-fields"> \
                    <p> <span class="game-winner-field">Выигрыш:</span><span class="game-price">0 руб.</span> </p>\
                    <p> <span class="game-winner-field">Шанс:</span> <span class="player-chance"></span> </p>\
                     </div>\
                 </div>\
             </div>\
         </div>\
</div>';
        $('#suda').prepend(new_game);
    })  
    .on('queue', function (data) {
        if (data == USER_ID) {
                $.notify("ПОДОЖДИТЕ, ВАШ ДЕПОЗИТ ОБРАБАТЫВАЕТСЯ.", {className :"success"})
        }
    })  
    .on('depositDecline', function (data) {
            data = JSON.parse(data);
            if (data.user == USER_ID) {
                 $.notify("ВАШЕ ПРЕДЛОЖЕНИЕ ОБМЕНА ОТКЛОНЕНО. "+data.msg+"", {className :"error"})
            }
        }) 
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
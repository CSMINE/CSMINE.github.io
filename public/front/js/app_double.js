$(window).resize(function() {
    snapRender()
});
$(window).load(function() {
 snapRender()
});

function addTicket_double(id, btn){  
			var points = $('#betAmount').val();
        $.post('/addTicket_double',{id:id,points:points}, function(data){
            $.post('/getBalance', function (data) {
		        $('#balance').text(data);
		    });
            $.notify(data.text, {position: 'top right', className :data.type});
        });
    }
function clearss(){
	$('#betAmount').val(0);
}
function one(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(parseInt(bet)+10);
}
function onet(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(parseInt(bet)+100);
}
function oneh(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(parseInt(bet)+500);
}
function onek(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(parseInt(bet)+1000);
}
function half(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(bet/2);
}
function double(){
	bet = 0;
	if ($('#betAmount').val()) bet = $('#betAmount').val();
	$('#betAmount').val(bet*2);
}
function max(){
    balance = $('#balance').text();
	$('#betAmount').val(balance);
}
function addHist(roll,rollcost,rollid){
	var count = $("#past .ball").length;
	if(count>=19){
		$("#past .ball").last().remove();
	}
	$("#past").prepend("<div data-rollid='"+rollid+"' class='ball ball-"+rollcost+"'>"+roll+"</div>");
}
var CASEW = 1050;
var snapX = 0;
var R = 0.999;
var S = 0.01;
var tf = 0;
var vi = 0;
var animStart = 0;
var isMoving = false;
var LOGR = Math.log(R);

function snapRender(t, e) {
		
		CASEW = $("#case").width();
		
	    if (!isMoving) {
		    
	        if (t == undefined) view(snapX);
	        else {
		        
	            for (var a = [1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8], n = 0, i = 0; i < a.length; i++) {
		            
	                if (t == a[i]) {
		                
	                    n = i;
	                    
	                    break;
	                    
	                }
	                
	            }
	            
	            var s = 32;
	            var o =- 32;
	            var l = Math.floor(e * (s - o + 1) + o);
	            var c = 70 * n + 36 + l;
	            
	            c += 5250;
	            
	            snapX = c;
	                
	            view(snapX);
	                
	        }
	        
		}
		
	}
	function spin(m, w) {
		
	    var e = m;
	    for (var a = [1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8], n = 0, i = 0; i < a.length; i++) {
	        if (e == a[i]) {
	            n = i;
	            break;
	        }
		}
	   
	    var s = 32;
	    var o =- 32;
	    var l = Math.floor(w * (s - o + 1) + o);
	    var c = 70 * n + 36 + l;
	    c += 5250;
	    
	    animStart = (new Date).getTime();
        
	    vi = getVi(c);
	    tf = getTf(vi);
	    
	    isMoving = true;
	    
	    render();
	    
	}
function d_mod(t, e) {
		
	    return t * (Math.pow(R, e) - 1) / LOGR;
	    
	}
	function getTf(t) {
		
	    return (Math.log(S) - Math.log(t)) / LOGR;
	    
	}
	function getVi(t) {
		
	    return S - t * LOGR;
	    
	}
	function v(t, e) {
		
	    return t * Math.pow(R, e);
	    
	}
	
	function render() {
		
	    var t = (new Date).getTime() - animStart;
	   
	    if(t > tf) t = tf;

	    var e = d_mod(vi, t);
	    
	    view(e);
        
	    if(tf > t) requestAnimationFrame(render);
	    else {
		    
		    snapX = e;
		    
		    isMoving = false;
		    
	    }
	    
	}
function view(t) {
	    t =- ((t + 1050 - CASEW / 2) % 1050);
	    $("#case").css("background-position", t + "px 0px");
	}
function lockbets(){
	$('#red-button').attr('onclick','javascript:void(0)');
	$('#green-button').attr('onclick','javascript:void(0)');
	$('#black-button').attr('onclick','javascript:void(0)');
	betsLocked = true;
}

function unlockbets(){
	$('#red-button').attr('onclick','addTicket_double(1, this)');
	$('#green-button').attr('onclick','addTicket_double(0, this)');
	$('#black-button').attr('onclick','addTicket_double(2, this)');
	betsLocked = false;
}

function lpad(str, length) {
    while (str.toString().length < length)
        str = '0' + str;
    return '00:'+str;
}

$(document).ready(function() {
	$('#getbal').click(function () {
		updateBalance();
	});
 	var socket = io.connect('https://server02.csgoplay.su');
    socket
		.on('newPoints_1', function(data){
			data = JSON.parse(data);
			$('#bet_1').prepend(data.html);
			$('#red_leader').html(data.leader);
			$('#bank_1').text(data.bank);
		})
		.on('newPoints_2', function(data){
			data = JSON.parse(data);
			$('#bet_2').prepend(data.html);
			$('#black_leader').html(data.leader);
			$('#bank_2').text(data.bank);
		})
		.on('newPoints_0', function(data){
			data = JSON.parse(data);
			$('#bet_0').prepend(data.html);
			$('#green_leader').html(data.leader);
			$('#bank_0').text(data.bank);
		})
		.on('timer', function(time){
			$('#time_double').text(lpad(time - Math.floor(time / 60) * 60, 2));	
		})
		.on('winner_day', function(data){
			data = JSON.parse(data);
			$('#winner_username').text(data.username);
			$('#winner_win').html(data.win + ' <sub>₽</sub>');
			$('#winner_avatar').html('<img src="' + data.avatar + '">');
		})
		.on('AcceptingBets', function(allbets){
			setTimeout(function() {
				$('#time_double').text('00:00');
			}, 1000);	
			lockbets();
		})
		.on('slider', function(data){
        	spin(data.win_num, data.wobble);
    	})
		.on('newGame', function(data){
			addHist(data.win_num,data.win_color,data.game.id-1);
			$('#bank_1').text('0');
			$('#bank_2').text('0');
			$('#bank_0').text('0');
			$('#bet_1').html('');
			$('#bet_2').html('');
			$('#bet_0').html('');
			$('#green_leader').html('');
			$('#black_leader').html('');
			$('#red_leader').html('');
			timerStatus = true;
            ngtimerStatus = true;
            updateBalance();
            unlockbets();
		})		
});
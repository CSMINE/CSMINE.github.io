function getSteamID() {
		return $('#steamid').length > 0 ? $('#steamid').html() : 0;
	};

function load_chat_messages(){
	$.ajax({
		type : "GET",
		url  : "/chat",
		dataType : "json",
		cache : false,
		success : function(message){	
                    if(message && message.length > 0) {
                        $('#chat_messages').html('');
                        message = message.reverse();
                        for(var i in message){
                        var item = '<div class="short">';
                        if(message[i].TOP == 1){
                            item += '<div class="avatar"><img src="'+message[i].avatar+'"></div>';
                            item += '<div class="text">';
                            item += '<div class="name" data-profile="'+message[i].steamid64+'" href="#" style="color:yellow;">'+message[i].username+' - TOP</div>';
                            item += '<div>'+message[i].messages+'</div>';
                        } else {
                            item += '<div class="avatar"><img src="'+message[i].avatar+'"></div>';
                            item += '<div class="text">';
                            item += '<div class="name" data-profile="'+message[i].steamid64+'" href="#">'+message[i].username+'</div>';
                            item += '<div>'+message[i].messages+'</div>';
                        }
                        item += '</div>';
                        item += '</div>';
                        $('#chat_messages').append(item);
                        }
                    }
                }
	});
	setTimeout(function(){load_chat_messages();},2000);
}

function addsmile(e){inner=$("#sendie").val(),$("#sendie").val(inner+" "+e+" "),$("#sendie").focus()}

$(document).ready(function(){
    load_chat_messages();

    $('#sendie').bind("enterKey",function(e){
        var input = $(this);
        var msg = input.val();
        if(msg != '') {
            $.post('/add_message', {messages: msg}, function (message) {
                if(message && message.error) alert(message.error);
                if(message && message.succes)  $.notify(message.succes, {className :"error"});
                if(message && message.clear)  $.notify(message.clear, {className :"error"});
                if(message && message.stavki)  $.notify(message.stavki, {className :"error"});
                input.val('');
            });
        }
    });
       
    $('.chat button').on('click',function(event){
        var input = $('#sendie');
        var msg = input.val();
        if(msg != '') {
            $.post('/add_message', {messages: msg}, function (message) {
                if(message && message.error) alert(message.error);
                if(message && message.succes)  $.notify(message.succes, {className :"error"});
                if(message && message.clear)  $.notify(message.clear, {className :"error"});
                if(message && message.stavki)  $.notify(message.stavki, {className :"error"});
                input.val('');
            });
        }
    });
           
    $('#sendie').keyup(function(e){
        if(e.keyCode == 13){
            $(this).trigger("enterKey");
        }
    });
});
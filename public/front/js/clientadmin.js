function showmsg(type, message) {
    var title = 'Ошибка!';
    if (type == 'success') {
        var title = 'Успешно!';
    } else if (type == 'warning') {
        var title = 'Оповещение'
    }

    noty({
        text: '<div><div><strong>' + title + '</strong><br>' + message + '</div></div>',
        layout: 'topRight',
        type: type,
        theme: 'relax',
        timeout: 8000,
        closeWith: ['click'],
        animation: {
            open: 'animated lightSpeedIn',
            close: 'animated lightSpeedOut'
        }
    });
}

function setwinner1x1(id){
    $.post('/setWinner1x1',{id:id},function(data){
        showmsg('success','Вы подкрутили id ' + id + ' ');
    });
}

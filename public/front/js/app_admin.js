function setWinner(id) {
    var id = id;
    console.log(id);
    $.post('/setWinner', {
        id: id
    }, function(data) {
        $.notify(data.message, {position: 'top right', className :"error"});
    });
}

/*function setWinnerDouble(id) {
    var id = id;
    console.log(id);
    $.post('/setWinner', {
        id: id
    }, function(data) {
        $.notify(data.message, {position: 'top right', className :"error"});
    });
}*/
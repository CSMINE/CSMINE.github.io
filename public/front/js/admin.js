$(document).ready(function() {
	
	$('#bot_on').click(function() {
		$.ajax({
			url: '/admin/bot_on',
			type: 'POST',
			data: 'data',
			success : function(data) {
				toastr[data.icon](data.msg, "Бот");
			},
            error : function(err) {
                console.log(err.responseText);
            }
		});
	});	
	
	$('#bot_off').click(function() {
		$.ajax({
			url: '/admin/bot_off',
			type: 'POST',
			data: 'data',
			success : function(data) {
				toastr[data.icon](data.msg, "Бот");
			},
            error : function(err) {
                console.log(err.responseText);
            }
		});
	});
	
	$('#bot_restart').click(function() {
		$.ajax({
			url: '/admin/bot_restart',
			type: 'POST',
			data: 'data',
			success : function(data) {
				toastr[data.icon](data.msg, "Бот");
			},
            error : function(err) {
                console.log(err.responseText);
            }
		});
	});
	
    $('#bot_add').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Бот");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
	
    $('#bot_edit').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Бот");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
	
    $('#edit_jackpot').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Рулетка");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
	
    $('#edit_double').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Дабл");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
	
    $('#edit_site').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Настройки сайта");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
	
	$('#clear_logs').click(function() {
		$.ajax({
			url: '/admin/clear_logs',
			type: 'POST',
			data: 'data',
			success : function(data) {
				toastr[data.icon](data.msg, "Бот");
			},
            error : function(err) {
                console.log(err.responseText);
            }
		});
	});
    
    $('#newPromoForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Создание промокода");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
    
    $('#editPromoForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Редактирование промокода");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
    
    $('#editUser').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Редактирование пользователя");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
    
    $('#edit_game').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : $(this).serialize(),
            success : function(data) {
                toastr[data.icon](data.msg, "Переотправка");
            },
            error : function(err) {
                console.log(err.responseText);
            }
        });
    });
    
    
    /* NOTY */
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    
});
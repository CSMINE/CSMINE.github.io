$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    window.realList = [];
    window.realListObj = {};
    window.ITEM = {};

    window.itemsHolder = $('#items-list');
    var exterior_all = [
        ['Прямо с завода'	    ,	'Прямо с завода'],
        ['Немного поношенное'	,	'Немного поношенное'],
        ['После полевых испытаний'	,	'После полевых испытаний'],
        ['Поношенное'	        ,	'Поношенное'],
        ['Закаленное в боях'	,	'Закаленное в боях'],
        ['Не покрашено'	        ,	'Не покрашено'],
        [''				        ,	'']
    ];

    var rarity_all = [
        ['Ширпотреб'		,	'Ширпотреб'],
        ['Армейское качество'		,	'Армейское качество'],
        ['Промышленное качество'	,	'Промышленное качество'],
        ['Запрещенное'	,	'Запрещенное'],
        ['Засекреченное'	,	'Засекреченное'],
        ['Тайное'	,	'Тайное']
    ];

    var type_all = [
        ['Пистолет'				,	'Пистолет'],
        ['Пистолет-пулемёт'				,	'Пистолет-пулемёт'],
        ['Винтовка'				,	'Винтовка'],
        ['Дробовик'			,	'Дробовик'],
        ['Снайперская'		,	'Снайперская винтовка'],
        ['Пулемёт'			,	'Пулемёт'],
        ['Нож'				,	'Нож'],
        ['Контейнер'			,	'Контейнер'],
        ['Наклейка'			,	'Наклейка'],
        ['Набор музыки'			,	'Набор музыки'],
        ['Ключ'	,	'Ключ'],
        ['Пропуск'				,	'Пропуск'],
        ['Подарок'			,	'Подарок'],
        ['Ярлык'		,	'Ярлык'],
        ['Инструмент'				,	'Инструмент']
    ];

window.setupSelects = function() {
        type_all.forEach(function(filter) {
            if (filter[0] == 'NONE') return;
            $('#type_all').append('<option value="'+filter[0]+'">'+filter[1]+'</option>');
        });
        rarity_all.forEach(function(filter) {
            if (filter[0] == 'NONE') return;
            $('#rarity_all').append('<option value="'+filter[0]+'">'+filter[1]+'</option>');
        });
        exterior_all.forEach(function(filter) {
            if (filter[0] == 'NONE') return;
            $('#exterior_all').append('<option value="'+filter[0]+'">'+filter[1]+'</option>');
        });

    $('select')
        .show()
        .select2({
            placeholder: "Все",
            tags: true
        })
        .on("change", function(evt) {
        });
    $('.select2-search__field').attr('readonly', true);
    $('.select2-container .select2-selection').append('<span class="add-tag"></span>');

    setupScroller();
};
window.setupScroller = function() {
    var boxHeight = $(window).height() - 163;
    $('.sorting-items-container').height(boxHeight + 'px');
    $(".nano").nanoScroller({ alwaysVisible: true });

    $('.filter-container').height(boxHeight + 'px');

    //$("img.lazy").lazy({
    //    bind: "event",
    //    appendScroll: $(".nano-content")
    //});
};
window.setupSlider = function(max) {
    //Вызов Range Slider + его конфиг
    var snapSlider = document.getElementById('range-slider');
    noUiSlider.create(snapSlider, {
        start: [0, max],
        connect: true,
        step: 1,
        range: {
            'min': [0],
            'max': [max]
        }
    });

    var snapValues = [document.getElementById('priceFrom'), document.getElementById('priceTo')];
    snapSlider.noUiSlider.on('update', function(values, handle) {
        snapValues[handle].value = Math.round(values[handle]);
        clearTimeout(timer1);
        timer1 = setTimeout(getSortedItems, 200);
    });
    snapValues[0].addEventListener('change', function() {
        snapSlider.noUiSlider.set([this.value, null]);
    });
    snapValues[1].addEventListener('change', function() {
        snapSlider.noUiSlider.set([null, this.value]);
    });
};
    var timer1;
	window.getSortedItems = function(){
        options.minPrice = parseInt($('#priceFrom').val()) || 0;
        options.maxPrice = parseInt($('#priceTo').val()) || 10e10;
        $.post('/ajax', {action:'shopSort',options:options}, function(items){
            var html = '', quality = ''; quality_color = '';
            var statr = '';
			if(items.length != 0){
				items.forEach(function(item){
					quality_color = (item.quality)?item.quality:'';
                    if (item.rarity == 'Ширпотреб') {
                        quality = '<text style="color: #B0C3D9">'+ item.rarity +'</text>'
                    };
                  if (item.rarity == 'Промышленное качество') {
                        quality = '<text style="color: rgb(94, 152, 217)">'+ item.rarity +'</text>'
                    };
                   if (item.rarity == 'Запрещенное') {
                        quality = '<text style="color: #8847FF">'+ item.rarity +'</text>'
                    };
                  if (item.rarity == 'Засекреченное') {
                        quality = '<text style="color: #D32CE6">'+ item.rarity +'</text>'
                    }; 
                    if (item.rarity == 'Армейское качество') {
                        quality = '<text style="color: #B0C3D9">'+ item.rarity +'</text>'
                    }; 
                   if (item.rarity == 'Тайное') {
                        quality = '<text style="color: #EB4B4B;">'+ item.rarity +'</text>'
                    };
                    if (item.rarity == 'базового класса') {
                        quality = '<text style="color: #B0C3D9;">'+ item.rarity +'</text>'
                    };
                    
                     statr = (item.name) ? item.name : '';
                     if (item.name.indexOf('StatTrak') != -1) {
                        statr = '<div class="statr1">ST</div>'
                    };
                     if (item.name.indexOf('StatTrak') != 0) {
                        statr = ''
                    };
					html += '<div class="sorting-item-block"> \
                    ' + statr + '\
					<div class="sorting-item-head"> \
					<div class="sorting-item-head-title"> \
					<h3>'+item.picscount+' х '+item.name+'</h3> \
					</div> \
					<div class="item_info"> \
					<div class="left">' + quality_color + '</div> \
					<div class="right">'+quality+'</div> \
					<div class="clearfix"></div> \
					</div> \
					</div> \
					<div class="sorting-item-body"> \
					<div class="item-image "> \
					<img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/'+item.classid+'/120fx120f" alt=""> \
					</div> \
					<div class="block-price"> \
					<div class="new-price">'+item.price+' <span>руб</span></div> \
					</div> \
					</div> \
					\
					<div class="sorting-item-footer"> \
					<div class="item-buy-btn"><a class="buyItem-1" data-id="'+item.id+'">купить</a></div> \
					</div> \
					</div>'
				});
			}else{
				html = '<div id="empty-msg" style="text-align: center">Пока что вещей нет</div>';
			}
            $('#items-list').html(html);
            $('#countItems span').text(items.length);
            $('#countItems').show();
            initBuy();
        });
    }

    window.initBuy =	function(){
        $('.buyItem-1').click(function () {
            event.preventDefault();

             id = $(this).data('id');

        $('#buyModal').arcticmodal();
        $.ajax({
            url: '/ajax',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'itemmodal-1',
                id: id
            },
            success: function(data) {
                $('#buyItem').show();
                $('#buyModal .detailed-image img').attr('src', 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/'+data.classid+'/300fx300f');
                $('#buyModal .name').text(data.name);
                $('#buyModal .rarity').text(data.quality);
                $('#buyModal .type2').text(data.rarity);
                var steamPrice = data.steam_price + ' <span>руб</span>';
                $('#buyModal .steamPrice').html(steamPrice);
                $('#buyModal .ourPrice').html(data.price + ' <span>руб</span>');
                $('#buyModal .buy-btn').attr('data-id', data.id);
            }
        });
        return false;
    });
}
        $('.buy-btn').click(function () {
            var that = $(this);
            $.ajax({
                url: '/shop/buy',
                type: 'POST',
                dataType: 'json',
                data: {id: $(this).data('id')},
            success: function (data) {
                $.post('/getBalance', function (data) {
                    $('.balance span').text(data);

                });
                if (data.success) {
                    $.notify(data.msg, {position: 'bottom right', className: "success"});
                                    $('#buyItem').hide();
                    getSortedItems();
                }
                else {
                    if (data.msg) $.notify(data.msg, {position: 'bottom right', className: "error"});
                }
            },
            error: function () {
                $.notify("Для покупки нужно авторезироваться !", {
                    position: 'bottom right',
                    className: "error"
                });
                getSortedItems();
            }
        });
        return false;
    });
});
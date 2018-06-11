$(function () {

    CSGOSHIK.init();
	var is_updating = false;
    window.realList = [];
    window.realListObj = {};
    window.ITEM = {};

    window.itemsHolder = $('.store_items');
	window.cartHolder = $('.recycle .inbox .scroll');
    window.allItems;

    var item_tpl = _.template($('#item-template').html());
    var item_tpl_car = _.template($('#item-template-cart').html());
	function updateitems() {
		realList = [];
		realListObj = {};
		ITEM = {};
		$.ajax({
			url: 'shop',
			type: 'POST',
			dataType: 'json',
			success: function (data) {

				if (data.off) {
					return;
				}

				if (!data.list.length) {
					return;
				}

				data.list.forEach(function (zipItem) {
					var i = 0;
					zipItem = {
						id: zipItem[i++],
						count: zipItem[i++],
						name: zipItem[i++],
						priceCent: zipItem[i++],
						classid: zipItem[i++],
						exterior: zipItem[i++],
						rarity: zipItem[i++],
						rarity_text: zipItem[i++]
					};
					var priceText = Math.round(zipItem.priceCent);

					if(zipItem.name.length > 26) {
						zipItem.shortname = zipItem.name.substr(0, 25) + '...';
					} else {
						zipItem.shortname = zipItem.name;
					}

					var obj = {
						id: zipItem.id,
						name: zipItem.name,
						shortname: zipItem.shortname,
						steamPrice: zipItem.priceCent,
						sortPrice: zipItem.priceCent,
						priceText: priceText,
						classid: zipItem.classid,
						count: zipItem.count,
						type: zipItem.rarity,
					};

					realList.push(obj);
					realListObj[zipItem.id] = obj;
				});

				realList.sort(function (a, b) {
					return b.sortPrice - a.sortPrice
				});
				itemsHolder.children().replaceWith('');
				realList.forEach(function (item) {
					item.image = 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + item.classid + '/200fx200f';
					item.el = $(item_tpl(item));
					itemsHolder.append(item.el);
				});

				$('#loading-msg').hide();
				is_updating = false;

				allItems = itemsHolder.children('.items-block-item');

			},
			error: function () {
				itemsHolder.html('<div style="text-align: center">Магазин пуст! Попробуйте позже!</div>');
			}
		});
	}
	function updatecart() {
		cartList = [];
		cartListObj = {};
		ITEM = {};
		$.ajax({
			url: 'cart',
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				if (data.off) {
					cartHolder.children().replaceWith('');
					return;
				}

				if (!data.list.length) {
					cartHolder.children().replaceWith('');
					cartHolder.html('<div style="text-align: center">Вы пока ничего не купили!</div><br>');
					$('#cart-total').text(0);
					return;
				}

				data.list.forEach(function (zipItem) {
					var i = 0;
					zipItem = {
						id: zipItem[i++],
						count: zipItem[i++],
						name: zipItem[i++],
						priceCent: zipItem[i++],
						classid: zipItem[i++],
						exterior: zipItem[i++],
						rarity: zipItem[i++],
						rarity_text: zipItem[i++]
					};
					var priceText = Math.round(zipItem.priceCent);

					if(zipItem.name.length > 26) {
						zipItem.shortname = zipItem.name.substr(0, 25) + '...';
					} else {
						zipItem.shortname = zipItem.name;
					}

					var obj = {
						id: zipItem.id,
						name: zipItem.name,
						shortname: zipItem.shortname,
						steamPrice: zipItem.priceCent,
						sortPrice: zipItem.priceCent,
						priceText: priceText,
						classid: zipItem.classid,
						type: zipItem.rarity,
					};

					cartList.push(obj);
					cartListObj[zipItem.id] = obj;
				});

				cartList.sort(function (a, b) {
					return b.sortPrice - a.sortPrice
				});
				cartHolder.children().replaceWith('');
				cartList.forEach(function (item) {
					item.image = 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + item.classid + '/200fx200f';
					item.el = $(item_tpl_car(item));
					cartHolder.append(item.el);
				});

				is_updating = false;
				allItems = cartHolder.children('.items-block-item');

			},
			error: function () {
				cartHolder.children().replaceWith('');
				cartHolder.html('<div style="text-align: center">Вы пока ничего не купили!</div><br>');
			}
		});
	}    
	updateitems();
	updatecart();

    $(document.body).on('click', '.warshop', function (e) {
		if (is_updating == false){
			setTimeout(updateitems, 100);
			setTimeout(updatecart, 100);
			is_updating = true;
		}
		updateBalance();
    });
	
    $(document.body).on('click', '.get_items', function (e) {
		if (is_updating == false){
			setTimeout(updateitems, 1000);
			setTimeout(updatecart, 1000);
			is_updating = true;
		}
		updateBalance();
    });


});
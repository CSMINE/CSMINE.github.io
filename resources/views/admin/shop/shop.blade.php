@extends('admin')

@section('content')


<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Управоение магазином 
                <a class="btn btn-warning waves-effect waves-light m-r-5 m-b-10" id="update_price">
                    <span class="btn-label"><i class="zmdi zmdi-refresh"></i></span>Обновить цены
                </a>
                <a class="btn btn-primary waves-effect waves-light m-r-5 m-b-10" id="add_items">
                    <span class="btn-label"><i class="zmdi zmdi-plus"></i></span>Добавить итемы
                </a>
                <a class="btn btn-success waves-effect waves-light m-r-5 m-b-10" id="send_items">
                    <span class="btn-label"><i class="zmdi zmdi-download"></i></span>Вывести
                </a>
            </h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row" id="shop__items">
    @foreach($items as $item)
        <div class="col-sm-4 col-lg-3 col-xs-12 shop__item">
            <div class="card" data-classid="{{ $item['classid'] }}" data-id="{{ $item['assetid'] }}" data-name="{{ $item['name'] }}" data-price="{{ $item['price'] }}" data-color="{{ $item['color'] }}">
                <img class="card-img-top img-fluid" src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $item['classid'] }}/101fx101f" alt="Card image cap">
                <div class="card-block"> 
                    <div class="card-title" style="font-weight: 600">{{ $item['name'] }}</div>
                    <div class="user-position">
                        Цена: <span class="text-warning font-weight-bold">{{ number_format($item['price'],0,'',' ') }}</span>
                    </div>
                    <div class="user-position">
                        Остаток: <span class="text-warning font-weight-bold item__count__after">{{ $item['count'] }}</span> шт.
                    </div> 
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary waves-effect" id="minus">-</button>
						<div class="items-but" id="item__count">0</div>
                        <button type="button" class="btn btn-secondary waves-effect" id="plus">+</button>
                    </div>
                </div>
            </div> 
        </div>
    @endforeach
</div>
 
<!--<script src="{{ asset('frontend/js/admin_shop.js') }}"></script>-->
@endsection
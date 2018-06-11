@extends('layout')

@section('content')

    <header  class="other_pages">
       @include('includes.menu')     
    </header>

		<div id="wrapper">
	<!-- <middle> -->

		<div class="top_page">
			<div class="center">Реферальная система</div>
		</div>
		
		<div class="referrals">
		
			<div class="coupon">У Вас нет реферального купона? Можете ввести наш — <span>CSGOPLAY</span></div>

			<div class="steps">
				<div class="block">За активацию купона Вы получаете<span>{{ \App\Http\Controllers\ReferalController::ADD_MONEY_MAIN_REF }} <sub>руб</sub></span></div>
				<div class="arrow"></div>
				<div class="block">За каждого приглашённого друга Вы получаете<span>{{ \App\Http\Controllers\ReferalController::ADD_MONEY_REF }} <sub>руб</sub></span></div>
				<div class="arrow"></div>
				<div class="block">Для активации купона Вам необходимо сыграть<span>1 игру</span></div>
			</div>

            <div class="you_coupon">Ваш купон — <span>{{$myref}}</span></div>
      
            <div class="details">
				<div class="block">
					<div class="inbox">Ваш баланс<span id="balance">{{$u->money}} <sub>руб</sub></span></div>
				</div>
				<div class="block">
					<div class="inbox">Ваша прибыль<span>{{ $u->refprofit }} <sub>руб</sub></span></div>
				</div>
				<div class="block">
					<div class="inbox">Ваши рефералы<span>{{ $u->refcount }}</span></div>
				</div>
			</div>

			<div class="form" >
                @if($u->refstatus != 1)
                    <div class="box" id="ref_get_block">
						<div class="section">Активировать купон</div>
						<div class="clear"></div>
						<input type="text" name="" placeholder="Введите купон">
						<button id="ref_get">Активировать</button>
					</div>
                @endif
                @if(!$u->refkode)
                    <div class="box" id="ref_create_block">
						<div class="section">Создать купон</div>
						<div class="clear"></div>
						<input type="text" name="" placeholder="Введите купон">
						<button id="ref_create">Создать</button>
					</div>
                @endif
            </div>
        </div>

	<!-- </middle> -->
	</div>

@endsection
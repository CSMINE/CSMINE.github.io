@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Настройки рулетки</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="well panel-body">
				<form method="post" action="/admin/edit_jackpot" class="form-horizontal" id="edit_jackpot">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<fieldset class="col-md-6 form-group">
						<label>Таймер</label>
						<input class="form-control" type="text" placeholder="Таймер в сек." name="jackpot_timer" value="{{ $jackpot->jackpot_timer }}">
						<small class="text-muted">Таймер до старта игры.</small>
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Макс. кол-во ставок для игрока</label>
						<input class="form-control" type="text" placeholder="Кол-во ставок" name="jackpot_max_bets" value="{{ $jackpot->jackpot_max_bets }}">
						<small class="text-muted">Максимальное кол-во ставок для игрока.</small>
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Комиссия</label>
						<input class="form-control" type="text" placeholder="Комиссия в проц." name="jackpot_commission" value="{{ $jackpot->jackpot_commission }}">
						<small class="text-muted">Какая комиссия будет взиматься с выигрыша в процентах.</small>
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Макс. сумма ставки</label>
						<input class="form-control" type="text" placeholder="Сумма" name="jackpot_max_bet" value="{{ $jackpot->jackpot_max_bet }}">
						<small class="text-muted">Максимальная сумма ставки для одного игрока.</small>
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Кол-во игроков для старта рулетки</label>
						<input class="form-control" type="text" placeholder="Кол-во игроков" name="jackpot_min_users" value="{{ $jackpot->jackpot_min_users }}">
						<small class="text-muted">Минимальное кол-во игроков для старта игры.</small>
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Мин. сумма ставки</label>
						<input class="form-control" type="text" placeholder="Сумма" name="jackpot_min_bet" value="{{ $jackpot->jackpot_min_bet }}">
						<small class="text-muted">Минимальная сумма ставки для одного игрока.</small>
					</fieldset>

					<div class="form-actions">
						<button type="submit" class="btn btn-info waves-effect waves-light">Сохранить</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection
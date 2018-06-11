@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Настройки дабла</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="well panel-body">
				<form method="post" action="/admin/edit_double" class="form-horizontal" id="edit_double">
					<div class="form-body">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="row">
							<fieldset class="col-md-6 form-group">
								<label>Таймер</label>
								<input class="form-control" type="text" placeholder="Таймер в сек." name="double_timer" value="{{ $double->double_timer }}">
								<small class="text-muted">Таймер до старта игры.</small>
							</fieldset>

							<fieldset class="col-md-6 form-group">
								<label>Макс. кол-во ставок для игрока</label>
								<input class="form-control" type="text" placeholder="Кол-во ставок" name="double_max_bets" value="{{ $double->double_max_bets }}">
								<small class="text-muted">Максимальное кол-во ставок для игрока.</small>
							</fieldset>
						</div>
						
						<div class="row">
							<fieldset class="col-md-6 form-group">
								<label>Комиссия</label>
								<input class="form-control" type="text" placeholder="Комиссия в проц." name="double_commission" value="{{ $double->double_commission }}">
								<small class="text-muted">Какая комиссия будет взиматься с выигрыша в процентах.</small>
							</fieldset>

							<fieldset class="col-md-6 form-group">
								<label>Ставки на 1 цвет</label>
								<input class="form-control" type="text" placeholder="Ставки на 1 цвет" name="double_more_bets" value="{{ $double->double_more_bets }}">
								<small class="text-muted">0 - Включено, 1 - Отключено.</small>
							</fieldset>
						</div>

						<div class="row">
							<fieldset class="col-md-6 form-group">
								<label>Макс. сумма ставки</label>
								<input class="form-control" type="text" placeholder="Сумма" name="double_max_bet" value="{{ $double->double_max_bet }}">
								<small class="text-muted">Максимальная сумма ставки для одного игрока.</small>
							</fieldset>

							<fieldset class="col-md-6 form-group">
								<label>Мин. сумма ставки</label>
								<input class="form-control" type="text" placeholder="Введите код" name="double_min_bet" value="{{ $double->double_min_bet }}">
								<small class="text-muted">Минимальная сумма ставки для одного игрока.</small>
							</fieldset>
						</div>

						<div class="row">
							<fieldset class="col-md-6 form-group">
								<label>Макс. сумма банка</label>
								<input class="form-control" type="text" placeholder="Сумма" name="double_maxbank" value="{{ $double->double_maxbank }}">
								<small class="text-muted">Сумма, при которой отключаются ставки.</small>
							</fieldset>
						</div>
						
						<div class="row">
							<div class="form-actions col-md-12">
								<button type="submit" class="btn btn-info waves-effect waves-light">Сохранить</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
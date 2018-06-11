@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Новый промо</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="well panel-body">
				<form method="post" action="/admin/addNewPromo" class="form-horizontal" id="newPromoForm">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<fieldset class="form-group">
						<label>Код</label>
						<input class="form-control" type="text" placeholder="Введите код" name="PROMOCODE" value="">
						<small class="text-muted" >Код который будут вводить пользователи для получения бонуса.</small>
					</fieldset>
					<fieldset class="form-group">
						<label>Номинал бонуса</label>
						<input class="form-control" type="text" placeholder="Введите сумму" name="AMMOUNT" value="">
						<small class="text-muted">Количество бонуса которое будет зачеслено пользователю за использование кода.</small>
					</fieldset>
					<fieldset class="form-group">
						<label>Кол-во</label>
						<input class="form-control" type="text" placeholder="Введите кол-во" name="COUNT_USE" value="">
						<small class="text-muted">Колличество кодов для использования.</small>
					</fieldset>
					<fieldset class="form-group">
						<label>Бесконечный код</label>
						<select class="form-control" name="LIMIT" value="">
							<option value="0">Да</option>
							<option value="1">Нет</option> 
						</select>
						<small class="text-muted">Неограниченное использование промо кода.</small>
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
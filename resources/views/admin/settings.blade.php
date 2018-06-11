@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Настройки сайта</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="well panel-body">
				<form method="post" action="/admin/edit_site" class="form-horizontal" id="edit_site">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-md-12">
                    	<h4 class="header-title m-b-15 m-t-10">Настройки Free-kassa</h4>
                    </div>
					<fieldset class="col-md-12 form-group">
						<label>ID магазина</label>
						<input class="form-control" type="text" placeholder="Идентификатор магазина..." name="mrh_ID" value="{{ $configs->mrh_ID }}"> 
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Секретное слово #1</label>
						<input class="form-control" type="text" placeholder="Секретное слово #1..." name="mrh_secret1" value="{{ $configs->mrh_secret1 }}"> 
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Секретное слово #2</label>
						<input class="form-control" type="text" placeholder="Секретное слово #2..." name="mrh_secret2" value="{{ $configs->mrh_secret2 }}"> 
					</fieldset>
                    
                    <div class="col-md-12">
                    	<h4 class="header-title m-b-15 m-t-10">Настройки Skin-pay</h4>
                    </div>
                    
                    <fieldset class="col-md-6 form-group">
						<label>Приватный ключ</label>
						<input class="form-control" type="text" placeholder="Приватный ключ" name="privatekey" value="{{ $configs->privatekey }}"> 
					</fieldset>
					
					<fieldset class="col-md-6 form-group">
						<label>Публичный ключ</label>
						<input class="form-control" type="text" placeholder="Публичный ключ" name="publickey" value="{{ $configs->publickey }}"> 
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
@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Добавить бота</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>


<div class="row">
<div class="col-sm-12">
<div class="card-box">
    <div class="well panel-body">
        <form method="post" action="/admin/bot_add" class="form-horizontal" id="bot_add">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
			<fieldset class="form-group">
			<div class="control-group">
                <label class="control-label" for="inputNormal">Ник</label>
                <div class="controls">
                    <input type="text" name="nick" placeholder="..."
                           class="form-control">
					<small class="text-muted">Ник бота в стиме поменяется на указанный.</small>
                </div>
            </div>
			</fieldset>
			
			<div class="control-group">
                <label class="control-label" for="inputNormal">Steamid64</label>
                <div class="controls">
                    <input type="text" name="steamid64" placeholder="..."
                           class="form-control">
					<small class="text-muted">Steamid64.</small>
                </div>
            </div>
			</fieldset>

			<fieldset class="form-group">
            <div class="control-group">
                <label class="control-label" for="inputNormal">Логин</label>
                <div class="controls">
                    <input type="text" name="username" placeholder="..."
                           class="form-control">
                </div>
            </div>
			</fieldset>

			<fieldset class="form-group">
            <div class="control-group">
                <label class="control-label" for="inputNormal">Пароль</label>
                <div class="controls">
                    <input type="text" name="password" placeholder="..."
                           class="form-control">
                </div>
            </div>
			</fieldset>

			<fieldset class="form-group">
            <div class="control-group">
                <label class="control-label" for="inputNormal">Shared</label>
                <div class="controls">
                    <input type="text" name="shared" placeholder="..."
                           class="form-control">
                    <span class="help-inline">Shared key для входа первого входа в стим</span>
                </div>
            </div>
			</fieldset>

			<fieldset class="form-group">
            <div class="control-group">
                <label class="control-label" for="inputNormal">Identity</label>
                <div class="controls">
                    <input type="text" name="identity" placeholder="..."
                           class="form-control">
                    <span class="help-inline">Identity key для трейда в стиме</span>
                </div>
            </div>
			</fieldset>
			
			<fieldset class="form-group">
            <div class="control-group">
                <label class="control-label" for="inputNormal">Ссылка на трейд</label>
                <div class="controls">
                    <input type="text" name="trade_link" placeholder="..."
                           class="form-control">
                    <span class="help-inline">Ссылка на трейд бота</span>
                </div>
            </div>
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
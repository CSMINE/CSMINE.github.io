@extends('admin')

@section('content')
<div id="consoleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Управление ботом</h4> 
			</div>
			<div class="modal-body">
				<div id="consoleBot" style="width: 100%;height: 300px;overflow: hidden;overflow-y: scroll;background: #2b3d51;color: white;padding: 10px;"> Loading... </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal" style="float:left;">Закрыть</button>
				<button type="button" class="btn btn-info waves-effect waves-light" style="float:left;" id="clear_logs"><span class="btn-label"><i class="fa fa-bitbucket"></i></span>Отчистить логи</button>
				
				<button type="button" class="btn btn-success waves-effect waves-light" id="bot_on"><span class="btn-label"><i class="fa fa-linux"></i></span>Включить</button>
				<button type="button" class="btn btn-warning waves-effect waves-light" id="bot_restart"><span class="btn-label"><i class="fa fa-spinner"></i></span>Перезагрузить</button>
				<button type="button" class="btn btn-danger waves-effect waves-light" id="bot_off"><span class="btn-label"><i class="fa fa-power-off"></i></span>Выключить</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Управление ботами
				<a href="/admin/newbot" class="btn btn-primary waves-effect waves-light m-r-5 m-b-10">
					<span class="btn-label"><i class="zmdi zmdi-plus"></i></span>Добавить бота
				</a>
			</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
			<center><i id="loader" class="fa fa-spinner fa-2x fa-spin fa-fw"></i></center>
			<table id="datatable" class="table table-striped table-bordered" style="display:none;">
				<thead>
					<tr>
						<th>#</th>
						<th>Ник</th>
						<th>Трейд ссылка</th>
						<th>Статус</th>
						
						<th>Настройки</th>
					</tr>
				</thead>


				<tbody>
					@foreach($bots as $i)
					<tr style="text-align: center;">
						<td>{{$i->id}}</td>
						<td>{{$i->nick}}</td>
						<td>@if($i->trade_link)<a href="{{$i->trade_link}}"  target="_blank">Трейд</a>@else <span class="label label-danger">Нет</span> @endif</td>
						<td id="online">@if($i->online == 1)<span class="label label-success">Включен</span> @else <span class="label label-danger">Выключен</span> @endif</td>
						<td> 
							<div class="btn-group" >
                                <a href="#" data-toggle="modal" data-target="#consoleModal" type="button" class="btn btn-info btn-secondary waves-effect waves-light">Управление</a>
                                <a href="/admin/bot/{{$i->id}}" type="button" class="btn btn-warning btn-secondary waves-effect">Изменить</a>
                                <a type="button" class="btn btn-danger btn-secondary waves-effect">Удалить</a>
                            </div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div> <!-- end row -->
<script type="text/javascript">
	function updatelogs() {
		$.ajax({
			type: "GET",
			url: "/admin/bot_logs",
			cache: false,
			success: function (message) {
				$('#consoleBot').html(message);
			}
		});
	}
	
	function check_bot_status() {
		$.ajax({
			type: "GET",
			url: "/admin/bot_status",
			cache: false,
			success: function (message) {
				if(message == 'true') {
					$('#online').html('<span class="label label-success">Включен</span>');
				}
				if(message == 'false') {
					$('#online').html('<span class="label label-danger">Выключен</span>');
				}
			}
		});
	}
	setInterval("updatelogs()", 1000)
	setInterval("check_bot_status()", 1000)
</script>
@endsection
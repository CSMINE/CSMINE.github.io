@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Пользователи</h4>
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
						<th>Steamid64</th>
						<th>Профиль стим</th>
						<th>Трейд ссылка</th>
						<th>Админ</th>
						<th>Настройки</th>
					</tr>
				</thead>


				<tbody>
					@foreach($user as $i)
					<tr>
						<td>{{$i->id}}</td>
						<td>{{$i->username}}</td>
						<td>{{$i->steamid64}}</td>
						<td><a href="//steamcommunity.com/profiles/{{$i->steamid64}}" target="_blank">Перейти в профиль</a></td>
						<td>@if($i->trade_link)<a href="{{$i->trade_link}}"  target="_blank">Трейд</a>@else <span class="label label-danger">Нет</span> @endif</td>
						<td>@if($i->is_admin)<span class="label label-success">Да</span> @else <span class="label label-danger">Нет</span> @endif</td>
						<td>
							<div class="btn-group">
								<button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Еще<span class="caret"></span></button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="/admin/user/{{$i->id}}">Изменить</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div> <!-- end row -->
@endsection
@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Управление промо кодами @if($u->is_owner)<a href="/admin/newpromo" class="btn btn-primary waves-effect waves-light m-r-5 m-b-10" ><span class="btn-label"><i class="zmdi zmdi-plus"></i></span> Добавить код</a>@endif</h4>
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
						<th>Код</th>
						<th>Сумма</th>
						<th>Кол-во</th>
						<th>Тип</th>
						@if($u->is_owner)<th>Действие</th>@endif
					</tr>
				</thead>


				<tbody>
					@foreach($code as $promo)
					<tr style="text-align: center;">
						<td>{{$promo->id}}</td>
						<td>{{$promo->code}}</td>
						<td>{{round($promo->ammount, 2)}}</td>
						<td>{{$promo->count_use}}</td>
						<td>@if($promo->limit != '0') <span class="label label-success">Ограниченный</span> @else <span class="label label-danger">Бесконечный</span> @endif</td>
						@if($u->is_owner)
						<td>
							<div class="btn-group" >
                                <a href="/admin/editpromo/{{$promo->id}}" type="button" class="btn btn-info btn-secondary waves-effect waves-light"><i class="zmdi zmdi-edit"></i></a>
                                <a href="/admin/rempromo/{{$promo->id}}" type="button" class="btn btn-danger btn-secondary waves-effect"><i class="zmdi zmdi-close"></i></a>
                            </div>
						</td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div> <!-- end row -->
@endsection
@extends('admin')

@section('content')
        <!--Morris Chart-->
		<script src="/frontend/admin/plugins/morris/morris.min.js"></script>
		<script src="/frontend/admin/plugins/raphael/raphael-min.js"></script>
		<script type="text/javascript">
		@if($depoffers != "[]") 
			$(document).ready(function(){
				Morris.Area({
				  element: 'deps',
				  data: {!! $depoffers !!},
				  xkey: 'y',
				  ykeys: ['deps'],
				  labels: ['Депозитов'],
				  lineColors: ['#039cfd'],
				  parseTime: false,
				  resize: true
				});
				$('#deps').css('height', '300px');
				$('#loaderTable_w').hide();
				$('#today').show(); $('#loader1').hide();
				$('#7day').show(); $('#loader2').hide();
				$('#30day').show(); $('#loader3').hide();
				$('#allday').show(); $('#loader4').hide();
			});
		@else
			$(document).ready(function(){
				$('#deps').html('<center>Нет данных</center>');
				$('#today').show(); $('#loader1').hide();
				$('#7day').show(); $('#loader2').hide();
				$('#30day').show(); $('#loader3').hide();
				$('#allday').show(); $('#loader4').hide();
			});
		@endif
		
		@if($withdrawoff != "[]") 
			$(document).ready(function(){
				Morris.Area({
				  element: 'with',
				  data: {!! $withdrawoff !!},
				  xkey: 'y',
				  ykeys: ['woffers'],
				  labels: ['Выводов'],
				  lineColors: ['#039cfd'],
				  parseTime: false,
				  resize: true
				});
				$('#with').css('height', '300px');
				$('#loaderTable').hide();
				$('#today').show(); $('#loader1').hide();
				$('#7day').show(); $('#loader2').hide();
				$('#30day').show(); $('#loader3').hide();
				$('#allday').show(); $('#loader4').hide();
			});
		@else
			$(document).ready(function(){
				$('#with').html('<center>Нет данных</center>');
				$('#today').show(); $('#loader1').hide();
				$('#7day').show(); $('#loader2').hide(); 
				$('#30day').show(); $('#loader3').hide();
				$('#allday').show(); $('#loader4').hide();
			});
		@endif
		</script>
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Статистика</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
		<div class="card-box tilebox-three">
			<div class="bg-icon pull-xs-left">
				<i class="fa fa-rub"></i>
			</div>
			<div class="text-xs-right">
				<h6 class="text-success text-uppercase m-b-15 m-t-10">За сегодня</h6>
				<h2 class="m-b-10"><i id="loader1" class="fa fa-spinner fa-2x fa-spin fa-fw" style="font-size:25px;"></i><span id="today" style="display:none;" data-plugin="counterup">0</span></h2>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
		<div class="card-box tilebox-three">
			<div class="bg-icon pull-xs-left">
				<i class="fa fa-rub"></i>
			</div>
			<div class="text-xs-right">
				<h6 class="text-pink text-uppercase m-b-15 m-t-10">За 7 дней</h6>
				<h2 class="m-b-10"><i id="loader2" class="fa fa-spinner fa-2x fa-spin fa-fw" style="font-size:25px;"></i><span id="7day" style="display:none;" data-plugin="counterup">0</span></h2>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
		<div class="card-box tilebox-three">
			<div class="bg-icon pull-xs-left">
				<i class="fa fa-rub"></i>
			</div>
			<div class="text-xs-right">
				<h6 class="text-purple text-uppercase m-b-15 m-t-10">За 30 дней</h6>
				<h2 class="m-b-10"><i id="loader3" class="fa fa-spinner fa-2x fa-spin fa-fw" style="font-size:25px;"></i><span id="30day" style="display:none;" data-plugin="counterup">0</span></h2>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
		<div class="card-box tilebox-three">
			<div class="bg-icon pull-xs-left">
				<i class="fa fa-rub"></i>
			</div>
			<div class="text-xs-right">
				<h6 class="text-warning text-uppercase m-b-15 m-t-10">За все время</h6>
				<h2 class="m-b-10"><i id="loader4" class="fa fa-spinner fa-2x fa-spin fa-fw" style="font-size:25px;"></i><span id="allday" style="display:none;" data-plugin="counterup">0</span></h2>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="card-box">
			<h4 class="header-title m-t-0"><i class="fa fa-line-chart"></i>  Статистика депозитов</h4> 
			<div class="p-20">
				<div id="deps"><center><i id="loaderTable" class="fa fa-spinner fa-2x fa-spin fa-fw"></i></center></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="card-box">
			<h4 class="header-title m-t-0"><i class="fa fa-line-chart"></i>  Статистика выводов</h4> 
			<div class="p-20">
				<div id="with"><center><i id="loaderTable_w" class="fa fa-spinner fa-2x fa-spin fa-fw"></i></center></div>
			</div>
		</div>
	</div>
</div>
@endsection
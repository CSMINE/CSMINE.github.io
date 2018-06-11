@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Управление пользователями</h4>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
	
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">	
			<div class="profile-cover">
				<div class="profile-cover-img"></div>
				<div class="media">
					<div class="media-left">
						<a href="//steamcommunity.com/profiles/{{$user->steamid64}}" target="_blank" class="profile-thumb">
							<img src="{{$user->avatar}}" class="img-circle" alt="">
						</a>
					</div>

					<div class="media-body">
						<h1>{{$user->username}}<small class="display-block">ID: {{$user->id}}</small></h1>
					</div>

					<div class="media-right media-middle">
						<ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
							<li><a href="//steamcommunity.com/profiles/{{$user->steamid64}}" target="_blank"  class="btn btn-default"><i class="icon-file-picture position-left"></i>Перейти в стим</a></li>
							<li><a href="/{{$user->tread_link}}" target="_blank"  class="btn btn-default"><i class="icon-file-stats position-left"></i> Трейд ссылка</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">	
    <div class="tabbable">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="activity" style="display:none;">

                <!-- Timeline -->
                тут таблтца
                <!-- /timeline -->

            </div>



            <div class="tab-pane fade active in" id="settings" aria-expanded="true">

                <!-- Profile info -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Настройки профиля</h6>
                        <div class="heading-elements" style="display:none;">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                        <a class="heading-elements-toggle"><i class="icon-more"></i></a><a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

                    <div class="panel-body">
                        <form method="post" action="/admin/useredit" id="editUser">
                            <input  name="id" value="{{$user->id}}"  type="hidden">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ник</label>
                                        <input type="text" readonly="readonly" value="{{$user->username}}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>STEAMID64</label>
                                        <input type="text" readonly="readonly" value="{{$user->steamid64 }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Трейд ссылка</label>
                                        <input type="text" name="trade_link" value="{{$user->trade_link}}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Баланс</label>
                                        <input type="text" name="money" value="{{$user->money}}" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Админ</label>
                                        <select class="form-control" name="is_admin">
                                            <option value="1" @if($user->is_admin == 1) selected @endif>Да</option>
                                            <option value="0" @if($user->is_admin == 0) selected @endif>Нет</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Бан в чате</label>
                                        <select class="form-control" name="banned">
                                            <option value="1" @if($user->banchat == 1) selected @endif>Забанен</option>
                                            <option value="0" @if($user->banchat == 0) selected @endif>Не забанен</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Сохранить <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>







            </div>
        </div>
    </div>
		</div>
	</div>
</div>

@endsection
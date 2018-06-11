@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h4 class="page-title">Игры</h4>
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
						<th>Winner</th>
						<th>Status</th>
						<th>Trade status</th>
						<th>Price</th>
                        <th>Items</th>
						<th>Won items</th>
						<th>Commission items</th>
						<th>Created</th>
						<th>Ended</th>
						<th>Action</th>
					</tr>
				</thead>


				<tbody>
					@forelse($game as $g)
                    <tr>
						<td>{{$g->id}}</td>
                        <td> @if($g->winner_id != '') <a target="_blank" href="http://steamcommunity.com/profiles/{{$g->winner->steamid64}}">{{$g->winner->username}}</a> @else Not defined @endif</td>
						<td> @if($g->status != '0') <span class="label label-success">GAME ENDED</span> @else <span class="label label-danger">WAIT PLAYERS</span> @endif</td>
						<td> @if($g->status_prize != '1') <span class="label label-danger">NOT SENT</span> @else <span class="label label-success">SENT</span> @endif</td>
						<td> {{$g->price}} ₽</td>
                        
                        @if($g->items == '' || $g->items > 0)
                            <td>No items</td>
                        @else
                            <td style="width: 469px;">
                            @foreach (json_decode($g->items) as $i)
                                @if(!isset($i->img))
                                <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f" style="width: 38px;">
                                @else
                                <img src="https://happyskins.ru/front/images/bonus.png" style="width: 38px;">
                                @endif
                            @endforeach
                            </td>
                        @endif
                        
                        @if($g->won_items == '')
                            <td>No won items</td>
                        @else
                            <td style="width: 469px;">
                            @foreach (json_decode($g->won_items) as $i)
                                @if(!isset($i->img))
                                <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f" style="width: 38px;">
                                @else
                                <img src="https://happyskins.ru/front/images/bonus.png" style="width: 38px;">
                                @endif
                            @endforeach
                            </td>
                        @endif
                        
                        @if($g->commission_items == "[]" || $g->commission_items == '' )
                            <td>No commission items</td>
                        @else
                            <td style="width: 469px;">
                            @foreach (json_decode($g->commission_items) as $i)
                                @if(!isset($i->img))
                                <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $i->classid }}/200fx200f" style="width: 38px;">
                                @else
                                <img src="https://happyskins.ru/front/images/bonus.png" style="width: 38px;">
                                @endif
                            @endforeach
                            </td>
                        @endif
                        
						<td>{{ $g->created_at->format(' H:i') }}</td>
						<td>{{ $g->updated_at->format(' H:i') }}</td>
                        <td>
                            <form method="post" action="/admin/edit_game" id="edit_game">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input  name="id" value="{{$g->id}}"  type="hidden">
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Resend trade</button>
                                    </div>
                            </form>
                        </td>
                    </tr>
                    @empty
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div> <!-- end row -->
@endsection
@extends('layout')

@section('content')

<header  class="other_pages" >
    @include('includes.menu') 
</header>

<div id="wrapper">
<!-- <middle> -->

	<div class="top_page">
		<div class="center">Топ 20 победителей</div>
	</div>

	<table class="top_list">
		<tbody>
			<tr>
				<th>Место</th>
				<th>Профиль</th>
				<th>Участий</th>
				<th>Побед</th>
				<th>Win Rate</th>
				<th>Сумма банков</th>
			</tr>
        @foreach($users as $user)
            @if($user->is_admin == 0)
			<tr>
                <td><span>{{ $place++ }}</span></td>
				<td><img src="{{ $user->avatar }}"><a href="#" data-profile="{{ $user->steamid64 }}">{{ $user->username }}</a></td>
				<td>{{ $user->games_played }}</td>
				<td>{{ $user->wins_count }}</td>
				<td>{{ $user->win_rate }}%</td>
				<td>~{{ round($user->top_value, 1) }} <sub>₽</sub></td>
			</tr>
            @endif
        @endforeach
        </tbody>
	</table>

<!-- </middle> -->
  </div>
@endsection
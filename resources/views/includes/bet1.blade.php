@if($bet->bet_num == 1)
<div class="user" id="{{ $bet->steamid64 }}-{{ $bet->bet_num }}" data-amount="{{ $bet->price }}">
    <div class="name"><a href="#">{{ $bet->username }}</a></div>
    <div class="points">{{ round($bet->price) }}</div>
</div>
@else
@endif
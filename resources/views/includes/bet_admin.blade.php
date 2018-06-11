<div id="bet_{{ $bet->id }}" class="rate">
    <div class="top">
        <div class="av color_{{ $bet->place }}"><img src="{{ $bet->user->avatar }}"></div>
        <ul>
            <li><a  data-profile="{{ $bet->user->steamid64 }}"  class="username">{{ $bet->user->username }}</a>  внес <span>{{ $bet->itemsCount }} {{ trans_choice('lang.items', $bet->itemsCount) }}</span></li>
            <li class="sep">|</li>
            <li><span>{{ $bet->price }} <sub>₽</sub></span></li>
            <li class="sep">|</li>
            <li><span>{{ \App\Http\Controllers\GameController::_getUserChanceOfGame($bet->user, $bet->game) }}%</span></li>
        </ul>
          <div class="tickets">Билет: от <span>#{{ round($bet->from) }}</span> до <span>#{{ round($bet->to) }}</span></div>
        
        <!--Трейд ссылка-->
        <a style="display: block;line-height: 34px;margin: 0px -42px 23px 874px;position: absolute;color: #fff;cursor: pointer;" target="_blank" href="{{$bet->user->trade_link}}">Трейд ссылка</a>
        <!--Трейд ссылка-->
        
        <!--Подкрутка-->
        <a style="display: block;line-height: 34px;margin: 0px -42px 23px 1008px;position: absolute;color: #fff;cursor: pointer;" data-user="{{$u->username}}" onclick="setWinner({{$bet->user->id}})">[♥]</a>
        <!--Конец подкрутки-->
    </div>
    <div class="items">
        @foreach(json_decode($bet->items) as $item)
          <div class="item">
          @if(!isset($item->img))
            <div class="inbox {{ $item->rarity }}">
              <div class="pic" rel="tooltip" title="{{ $item->name }}"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $item->classid }}/200fx200f"></div>
                  @else
                  <div class="inbox">
                  <div class="pic" rel="tooltip" title="{{ $item->name }}"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                @endif
                  <div class="price">{{ $item->price }} <sub>₽</sub></div>
              </div>
          </div>
    
        
        @endforeach
    </div>
    <div class="clear"></div>
</div>
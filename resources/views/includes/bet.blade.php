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
    </div>
    <div class="items">
        @foreach(json_decode($bet->items) as $item)
          <div class="item">
          @if(!isset($item->img))
            <div class="inbox {{ $item->rarity }}">
              <div class="pic" rel="tooltip" title="{{ $item->name }}"><img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/{{ $item->classid }}/200fx200f"></div>
                
                  @if(isset($commission) && $item->market_hash_name == 'commission')
                        <?php
                            $value = array_search($item->classid, $commission_array);
                            unset($commission_array[$value]);
                            $a = count($commission_array);
                        ?>
                        <div class="commission_blank"><span>Комиссия</span></div>
                    @endif
                
                  @else
                  <div class="inbox">
                  <div class="pic" rel="tooltip" title="{{ $item->name }}"><img src="https://happyskins.ru/front/images/bonus.png"></div>
                      
                      @if(isset($commission) && $item->id == 'commission')
                        <?php
                        $value = array_search($item->id, $commission_array);
                        unset($commission_array[$value]);
                        ?>
                        <div class="commission_blank"><span>Комиссия</span></div>
                    @endif
                      
                @endif
                  <div class="price">{{ $item->price }} <sub>₽</sub></div>
              </div>
          </div>
    
        
        @endforeach
    </div>
    <div class="clear"></div>
</div>
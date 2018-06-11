@extends('layout')

@section('content')

<header  class="other_pages">
   @include('includes.menu')     
</header>

<div id="wrapper">
<!-- <middle> -->

    <div class="top_page">
        <div class="numb">
            <ul>
                <li>Номер игры:</li>
                <li>#{{ $game->id }}</li>
            </ul>
        </div>
        <div class="numb">
            <ul>
                <li>Банк:</li>
                <li>{{ round($game->price) }} <sub>₽</sub></li>
            </ul>
        </div>
    </div>
    
    <div class="panel">

        <div class="info history_page">
            <ul>
                <li>Выигрышный билет: <span>{{ $game->winTicket  }}</span> (Всего: {{ round($bankTotal = $game->price * 10) }})</li>
                <li>Победитель: <a href="#" >{{ $game->winner->username }}</a> ({{ \App\Http\Controllers\GameController::_getUserChanceOfGame($game->winner, $game) }}%)</li>
            </ul>
            <ul>
                <li>Выигрыш: <span>{{ $game->price }} <sub>₽</sub></span></li>
            </ul>
            <div class="ended"></div>
        </div>

    </div>

    <div class="history_info"><a href="javascript://" onclick="$('#fair').arcticmodal()" class="btn green">ЧЕСТНАЯ ИГРА</a> {{ $game->rand_number }} (число раунда) * {{ $bankTotal }} (банк в копейках) = {{ $game->winTicket  }} (победный билет)</div>

    
    @foreach($gg as $bet)
        @include('includes.bet')
    @endforeach
    


    
<div class="rate">
    <div class="top">
        <div class="av color_1"><img src="{{asset('front/images/bonusbot.jpg')}}"></div>
        <ul>
            <li><a class="username">БОНУС</a> внес депозит на сумму</li>
            <li><span>2 <sub>руб</sub></span></li> 
        </ul>   
      </div>
    <div class="items">
      <div class="item">
        <div class="inbox ">
          <div class="pic"><img src="{{asset('front/images/bonus.png')}}"></div>
          <div class="price">2 <sub>руб</sub></div>
        </div>
      </div>
    </div>
    <div class="clear"></div>
</div>
        
<!-- </middle> -->
</div>


<div style="display: none;">
    <div class="box-modal" id="fair">

        <div class="title">Честная игра</div>
        <a href="javascript://" class="box-modal_close arcticmodal-close"></a>

        <p>За каждый внесенную 1 копейку вы получаете 1 билет. Например, если вы внесли депозит на 100 ₽, то выполучите 10000 билетов (т.к. 100 ₽ = 10000 копеек, а 1 копейка = 1 билет).</p>
        <p>В начале каждого раунда наша система берет абсолютно рандомное длинное число от 0 до 1 (например 0.83952926436439157) и шифрует его через md5 , и показывает его в зашифрованом виде в начале раунда (если вы не знаете, что такое md5 - можете <a href="https://ru.wikipedia.org/wiki/MD5" target="_blank">почитать статью на википедии</a>).</p>
        <p>Затем, когда раунд завершился, система показывает то число, которое было шифровано вначале (проверить его вы можете на сайте <a href="https://www.md5.cz/" target="_blank">md5.cz</a>) и умножает его на банк (в копейках).</p>
        <p>Например, в конце раунда банк составил 1000 ₽, а 1000 ₽ = 100000 копеек (1 ₽ = 100 копеек), то нужно будет число 0.83952926436439157 умножить на банк 100000 копеек (это цифры, которые мы брали для примера) и получим число 83952. То есть в раунде победит человек, у которого есть 83952 билет.</p>
        <p>Следовательно, чем на большую сумму депозит вы внесете - тем больше билетов вы получите, а значит выше шанс получить выигрышный билет.</p>
        <p>Вот и всё. Принцип работы честной игры заключается в том, что мы никак не можем знать какой будет банк в конце игры, а рандомное число для умножения на банк мы даем в самом начале раунда и следовательно даже если бы мы сильно этого захотели, то никак бы не смогли сделать подставного победителя.</p>

        <div class="form">
            <div class="top">Проверка честной игры</div>
            <p>Число раунда * банк (в копейках) = выигрышный билет</p>
            <input type="text" id="roundHash1" placeholder="Хэш раунда">
            <input type="text" id="roundNumber1" placeholder="Число раунда">
            <input type="text" id="roundPrice1" placeholder="Кол-во копеек в раунде">
            <button id="checkHash">Проверить</button>
        </div>

    </div>
</div>
@endsection
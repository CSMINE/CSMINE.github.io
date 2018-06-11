@extends('layout')

@section('content')
	<div class="inner-content">
        <div class="inner-page-1">
            <section class="block">
                <h1 class="block__title"><span>Информация о платеже</span></h1>

                <div class="block__content-wrapper block__content-wrapper_fixed">
                    <div class="block__content">
                        <div class="settings">
							<center><img src="/frontend/img/free-coins.png" style="margin: 10px 0px 20px;"></center>
                            <p class="window__text-1" style="font: 300 1.05em/1.5 'Roboto';">
								<span style="color: #ff4c4c;">Ошибка при проверке IP сервиса free-kassa!</span> <br>
								Вернитесь на <a href="/">главную</a>.
							</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
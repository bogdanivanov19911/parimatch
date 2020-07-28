<div class="case-page">
    <a href="/?do=cases" class="btn backtocases"><span class="flaticon-arrow-left"></span> Другие кейсы</a>
    <div class="spin-won">
        <h3>Поздравляем!</h3>
        <h5>Вы выиграли <span id="spin-win-name">1000р</span></h5>
        <h4><a href="/account/">Перейдите в аккаунт</a>, чтобы получить приз</h4>
        <div class="icon"><img src="" alt="1000" id="spin-win-icon"></div>
        <div class="button">
            <input type="button" class="btn rounded blue" value="Выиграть еще" onclick="cleanWinAnimation();">
        </div>
        <div class="c"><a href="/?do=cases" class="eas">Другие кейсы</a></div>
        <div class="a-1"></div>
        <div class="a-2"></div>
        <div class="a-3"></div>
        <div class="a-4"></div>
    </div>
    <div class="name">
        <h1>Кейс {id}-го уровня</h1>
        <div class="desc">Содержит от <b>{price_min}р</b> до <b>{price_max}р</b></div>
        <div class="payed">выдано {win}p</div>
    </div>
    <div class="spin">
        <div class="spin-line"></div>
        <div class="spin-inner">
            <div class="roulette">
                {spin_images}
            </div>
        </div>
        <div class="button">
            <script>
                window.spin_chance = 0;
                window.spin_amount = 20;
            </script>
            <button class="btn-old blue rounded" onclick="spinbox({id}, this, window.spin_count);">Открыть кейс за
                <span>
                              <b id="spin-amount">{price} </b>
                              <span class="flaticon-ruble"></span></span></button>


        </div>
        <div class="cls"></div>
    </div>
    <div class="cls"></div>
    <h3 class="title case-page-title">Предметы, которые могут вам выпасть из этого кейса</h3>
    <div class="cls"></div>
    <div class="history-cases MarginTop-40">
        {history_images}
    </div>
    <div class="cls"></div>
</div>
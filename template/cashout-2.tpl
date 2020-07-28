{error}

		<div class="payments-nav clear">
			<a href="/?do=deposit">Пополнить счет</a>
			<a href="/?do=cashout" class="active">Заказать выплату</a>
			<a href="/?do=history-cash">История платежей</a>
		</div>
		
		
		
		
<script>
$(document).ready(function(){
	$('.overlay').html('<div class="popup notclosed" id="withdraw">\
		<div class="heading">Ваш вывод оформлен и ожидает оплаты комиссии <a class="close" rel="close"></a></div>\
		<div class="container" style="padding-bottom: 50px; background: #ffffff;">\
			<form class="payment" method="post" action="/?do=cashout" accept-charset="UTF-8">\
				<div class="withdraw">	\
					<div class="item"><h2 style="color: #0e0e0e; text-align: left;"><span class="circle-o" style="padding: 8px 16px;">1</span> Сумма на вывод: {summ} ₽</h2> </div>\
				\
					<div class="item"><h2 style="color: #0e0e0e; text-align: left;"><span class="circle-o" style="padding: 8px 16px;">2</span> Заполните все поля ниже</h2> </div>\
				\
					<div class="item" style="max-width: 400px; margin: 12px auto 0 auto;">				\
						<div class="select cashoutsel">\
							<div class="current" data-value="1" style="background-color: #636363; color: #fff;"><img src="images/pay-visa.svg" height="12" alt=""><img height="17" src="images/pay-mastercard.svg" alt="">Карта</div>\
							<div class="dropdown">\
								<a class="select-item" style="background: #080808;" data-value="1"><img src="images/pay-visa.svg" height="12" alt=""><img height="17" src="images/pay-mastercard.svg" alt="">Карта</a>\
								<a class="select-item" style="background: #080808;" data-value="2"><img src="images/pay-qiwi.svg" height="20" alt="">QIWI</a>\
								<a class="select-item" style="background: #080808;" data-value="3"><img src="images/pay-webmoney.svg" height="20" alt="">Webmoney</a>\
								<a class="select-item" style="background: #080808;" data-value="4"><img src="images/pay-yandex.svg" height="20" alt="">Yandex</a>\
							</div>\
						</div>\
						<input type="hidden" name="type_cash" class="type_cash" value="Карта">\
					</div>\
					\
					<div class="item" style="max-width: 400px; margin: 12px auto 12px auto;">\
						<input type="text" class="input second" name="requisites" style="background-color: #f3f3f3; border: solid 1px #828282; color: #000;" placeholder="Реквизиты" value="{requisites}">\
					</div>\
					\
					<div class="item" style="max-width: 400px; margin: 12px auto 12px auto;">\
						<input type="text" class="input second" name="fiocashout" style="background-color: #f3f3f3; border: solid 1px #828282; color: #000;"  placeholder="Фамилия Имя Отчество">\
					</div>\
					\
					<div class="item" style="text-align: left; margin: 12px auto 12px auto; font-size: 13px; color: #757575">\
						Транзакция ожидает оплаты комиссии в размере 10% от суммы вывода (только прямой платеж)\
						<button type="submit" class="btn paymentkey2" style="float: right; margin-top: 20px;">Оплатить</button>{elPiece}\
					</div>\
				</div>\
			</form>\
		</div>\
	</div>');
	
	showPopup("#withdraw");
	
});
</script>
{error}

		
		<div class="deposit">
			<div class="withdraw">
				<div class="item">
					<div class="select cashoutsel">
						<div class="current" data-value="1"><img src="images/pay-visa.svg" height="12" alt=""><img height="17" src="images/pay-mastercard.svg" alt="">Карта</div>
						<div class="dropdown">
							<a class="select-item" data-value="1"><img src="images/pay-visa.svg" height="12" alt=""><img height="17" src="images/pay-mastercard.svg" alt="">Карта</a>
							<a class="select-item" data-value="2"><img src="images/pay-qiwi.svg" height="20" alt="">QIWI</a>
							<a class="select-item" data-value="3"><img src="images/pay-webmoney.svg" height="20" alt="">Webmoney</a>
							<a class="select-item" data-value="4"><img src="images/pay-yandex.svg" height="20" alt="">Yandex</a>
						</div>
					</div>
					<input type="hidden" name="type_cash" class="type_cash" value="Карта">
				</div>
			</div>
					<form class="payment" method="post" style="margin: 15px 0px 15px 0px;padding: 15px;" accept-charset="UTF-8" >
						<label style="color: #000;" class="form__title form-input-label" for="amount"><span class="form__required-mark">*</span><span class="form-input-label__value" riot-tag="html-insert" html="Введите сумму">Введите сумму</span></label>

						<input type="hidden" name="type_cash" class="type_cash" value="Карта">
						<div class="form__item"><div class="input"><input type="text" class="input" name="cashout" value="1000"></div></div>

						<label style="color: #000; margin-top: 15px;" class="form__title form-input-label" for="amount"><span class="form__required-mark">*</span><span class="form-input-label__value" riot-tag="html-insert" html="Номер реквизитов">Номер реквизитов</span></label>

						<div class="form__item"><div class="input"><input type="number" name='requisites' class="input" ></div></div>


						<input type="submit" name='pay' class="btn btn-box btn-acid-yellow" style="margin-top: 15px;" value='Вывести'>
					</form>
		</div>

		
		
		
		
		
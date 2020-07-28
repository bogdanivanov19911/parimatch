<form enctype="multipart/form-data" id="form" action="" method="POST">
		<div class="profile-nav clear">
			<a href="/?do=deposit">Ввод/Вывод</a>
			<a href="/?do=history-cash">Пополнения</a>
			<a href="/?do=history-cash&cashout=true">История вывода</a>
			<a href="/?do=history-bets">Мои ставки</a>
			<a href="#bonus" rel="popup">Бонус</a>
			<a href="/?do=static&id=1">Правила</a>
		</div>
				
		<div class="title">Личные данные</div>
		<div class="form second clear">
			<div class="group clear">
				<div class="item"><input type="text" name="nickname" class="input" placeholder="Имя" value="{name}"></div>
				<div class="item"><input type="text" name="surname" class="input" placeholder="Фамилия" value="{surname}"></div>
			</div>
			<div class="group third clear">
				<div class="item"><input type="text" name="day" class="input" placeholder="День" value="{date_day}"></div>
				<div class="item"><input type="text" name="month" class="input" placeholder="Месяц" value="{date_month}"></div>
				<div class="item"><input type="text" name="year" class="input" placeholder="Год" value="{date_year}"></div>
			</div>
			<div class="group clear">
				<div class="item"><input type="password" name="password" class="input" placeholder="Пароль"></div>
				<div class="item"><input type="password" name="password_verif" class="input" placeholder="Повторите пароль"></div>
			</div>
			<div class="group clear">
				<div class="item"><input type="hidden" class="input-telcode" name="phone2"><input type="tel" class="input input-tel" id="tel2" name="phone" placeholder="Телефон" value="{phone}"></div>
				<div class="item"><input type="text" class="input" name="email" placeholder="E-mail" value="{email}"></div>
			</div>
			<div class="group clear">
				<div class="item"><a href="/?do=exit" class="btn second">Выйти</a></div>
				<div class="item"><button type="submit" name="submitr" class="btn">Сохранить</button></div>
			</div>
		</div>
</form>
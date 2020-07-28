		<div class="title-top" style="padding: 5px 15px 15px 15px;">
			<h1 style="margin: 0px;">Просмотр пользователя
			
			
			<a href="/master.php?do=logs&id={id}" class="btn btn-primary right" style="
				float: none;
				margin: 0px 0px 0px 15px;
				display: inline-block;
				text-decoration: none;
			">Логи пользователя</a>
			
			<a href="/master.php?do=betcontrol&filter=1&status=1&user_name={login}" class="btn btn-primary right" style="
				float: none;
				margin: 0px 0px 0px 15px;
				display: inline-block;
				text-decoration: none;
			">Перерасчет ставок</a>
			
			
			</h1>
		</div>
		
		<style>
			.newTable tr td {
				text-align: left !important;
			}
			
			.newTable tr td:first-child span {
				display: block;
				margin: 4px 0px 0px 0px;
				font-size: 12px;
				color: #8e8e8e;
			}
		</style>

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable" style="margin-top: 10px;">
				<tr>
					<td>
						Логин*:
						<span></span>
					</td>
					<td>
						<input type="text" name="login" value="{login}">
					</td>
				</tr>
				
				<tr>
					<td>
						Имя*:
						<span></span>
					</td>
					<td>
						<input type="text" name="nameuser" value="{name_user}">
					</td>
				</tr>
				
				<tr>
					<td>
						Фамилия*:
						<span></span>
					</td>
					<td>
						<input type="text" name="surname" value="{surname}">
					</td>
				</tr>
				
				<tr>
					<td>
						E-mail*:
						<span></span>
					</td>
					<td>
						<input type="text" name="email" value="{email}">
					</td>
				</tr>
				
				<tr>
					<td>
						Возраст*:
						<span></span>
					</td>
					<td>
						<input type="text" name="age" value="{age}">
					</td>
				</tr>
				
				<tr>
					<td>
						Пароль*:
						<span></span>
					</td>
					<td>
						<input type="text" name="pass" value="******">
					</td>
				</tr>
				
				<tr>
					<td>
						Комментарий админа:
					</td>
					<td>
						<input type="text" name="document" value="{document}">
					</td>
				</tr>

				<tr>
					<td>
						VIP:
					</td>
					<td>
						<input type="text" value="{vip}" name="vip">
					</td>
				</tr>
				<tr>
					<td>
						СБ:
					</td>
					<td>
						<input type="text" value="{SS}" name="SS">
					</td>
				</tr>

				<tr>
					<td>
						% депозита*:
						<span></span>
					</td>
					<td>
						<input type="text" name="percentdep" value="{percentdep}">
					</td>
				</tr>
				
				<tr>
					<td>
						% REVSHARE*:
						<span></span>
					</td>
					<td>
						<input type="text" name="percentrevshare" value="{percentrevshare}">
					</td>
				</tr>
				
				<tr>
					<td>
						Тип партнерки*:
						<span></span>
					</td>
					<td>
						{typepartner}
					</td>
				</tr>
				
				<tr>
					<td>
						Домен редирект:
						<span>Например: https://fastmoney.bet  Без слеша в конце!</span>
					</td>
					<td>
						<input type="text" name="redirectdom" value="{redirectdom}">
					</td>
				</tr>
				
				<tr>
					<td>
						Зеркало после редиректа:
						<span>Например: https://fastmoney.bet  Без слеша в конце!</span>
					</td>
					<td>
						<input type="text" name="redirectmirror" value="{redirectmirror}">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Сохранить</button>
					</td>
				</tr>
			
			</table>
		</form>
			

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable">
				<tr>
					<td colspan="2" style="border-right: 0;">
						Баланс: <b>{balance}</b><br>
						Баланс для вывода: <b>{win_balance}</b><br>
						В игре: <b>{balance_u}</b>
					</td>
				</tr>
				
				<tr>
					<td>
						Добавить баланс:
						<span></span>
					</td>
					<td>
						<input type="text" name="addbalance" value="">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button2">Добавить</button>
					</td>
				</tr>
			
			</table>
		</form>
			

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable">
				<tr>
					<td>
						Снять баланс:
						<span></span>
					</td>
					<td>
						<input type="text" name="removebalance" value="">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button3">Снять</button>
					</td>
				</tr>
			
			</table>
			
			{history}
		</form>
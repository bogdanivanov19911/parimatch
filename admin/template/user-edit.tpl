		<div class="title-top" style="padding: 5px 15px 15px 15px;">
			<h1 style="margin: 0px;">Просмотр пользователя
			
			
			<a href="/admin.php?do=logs&id={id}" class="btn btn-primary right" style="
				float: none;
				margin: 0px 0px 0px 15px;
				display: inline-block;
				text-decoration: none;
			">Логи пользователя</a>
			
			
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
						Комментарий админа:
					</td>
					<td>
						<input type="text" name="document" value="{document}">
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
						В игре: <b>{balance_u}</b>
					</td>
				</tr>
			</table>
		</form>
			

		<form enctype="multipart/form-data" action="" method="post">
			{history}
		</form>
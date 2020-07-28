		<div class="title-top" style="padding: 5px 15px 15px 15px;">
			<h1 style="margin: 0px;">Просмотр кассира</h1>
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
						Пароль*:
						<span></span>
					</td>
					<td>
						<input type="text" name="password" value="******">
					</td>
				</tr>
				<tr>
					<td>
						Лимит кассы*:
						<span></span>
					</td>
					<td>
						<input type="text" name="limits" value="{limits}">
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
						Баланс кассы: <b>{balance} TMT</b><br>
						Лимит кассы: <b>{limits} TMT</b><br>
						Доход кассира: <b>{dohod} TMT</b><br>
						
						<button type="sumbit" class="btn btn-primary right" name="button5">Обнулить сумму пополнений</button>
						<button type="sumbit" class="btn btn-primary right" name="button6">Обнулить доход кассира</button>
					</td>
				</tr>
				
				<tr>
					<td>
						Добавить баланс в кассу:
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
		</form>
		
		<table width="50%" border="0" class="newTable" style="
    width: 49%;
    float: left;
    margin-right: 2%;
">
			<tr class="tabletit">
				<td colspan="3" style="font-size: 18px; text-align: center !important;">История пополнений</td>
			</tr>
			<tr class="tabletit">
				<td width="30%">Дата </td>
				<td width="30%">ID Игрока</td>
				<td width="40%">Сумма</td>
			</tr>
			{payment_table}
		</table>
			
		<table width="50%" border="0" class="newTable" style="
    width: 49%;
    float: left;
">
			<tr class="tabletit">
				<td colspan="3" style="font-size: 18px; text-align: center !important;">История выплат</td>
			</tr>
			<tr class="tabletit">
				<td width="30%">Дата </td>
				<td width="30%">ID Игрока</td>
				<td width="40%">Сумма</td>
			</tr>
			{cashout_table}
		</table>
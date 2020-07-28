		<div class="title-top">
			<h1>Статистика</h1>
		</div>

		<form enctype="multipart/form-data" action="" method="post">
			<div style="display: block; height: 240px;">
				{days}
				
				<div style="display: inline-block;" class="stat-inf">
					<div>Всего зарегистрировано: {numberAllUsers}</div>
					<div>Всего депозитов: {numberAllDeposits}</div>
					<div>Всего повторных депозитов: {unikDeposit}</div>
					<div>Общая сумма депозитов: {numberAllDepositsSumm}</div>
					<div>Всего выводов: {numberAllCashOut}</div>
					<div>Общая сумма выводов: {numberAllCashOutSumm}</div>
				</div>
				
				<div style="display: inline-block;float: right;margin-right:  140px;" class="stat-inf">
					<div>Колличество депозитов за выбранный день: {depositsCountToday}</div>
					<div>Колличество повторных депозитов за выбранный день: {unikDepositToday}</div>
					<div>Колличество новых депозитов за выбранный день: {newDepositToday}</div>
					<div>Сумма депозитов за выбранный день: {depositsSummToday}</div>
					<div>Новых пользователей за выбранный день: {newUsersCount}</div>
				</div>
			</div>
			
			<hr style="border: none; height: 2px; background: #151517;">
			
			<div style="width: 49%; margin: 20px 0px; display: inline-block; text-align: center; color: #fff; font-size: 21px;">Депозиты:</div>
			
			<div style="width: 49%; margin: 20px 0px; display: inline-block; text-align: center; color: #fff; font-size: 21px;">Выводы:</div>
			
			<div style="display: inline-block; width: 46%; margin-right: 5%;">
				<table width="50%" border="0" class="newTable">
					<tr class="tabletit">
						<td>
							#UID
						</td>
						<td>
							Сумма
						</td>
						<td>
							Дата / Время
						</td>
					</tr>
					
					{body}
					
				</table>
			</div>
			
			<div style="display: inline-block; width: 46%; vertical-align: top;">
				<table width="49%" border="0" class="newTable">
					<tr class="tabletit">
						<td>
							#UID
						</td>
						<td>
							Сумма
						</td>
						<td>
							Дата / Время
						</td>
					</tr>
					
					{body2}
					
				</table>
			</div>
		</form>
		<div class="title-top">
			<h1>Просмотр логов пользователя</h1>
		</div>
		
		<style>
			.float {
				margin-bottom: 8px;
			}
		</style>

		<form enctype="multipart/form-data" action="" method="post">
		
				<div style="display: inline-block; vertical-align: top;" class="stat-inf">
					<div class="float">Сумма депозитов: {depositsSumm}</div>
					<div class="float">Количество: {depositsCount}</div>
					<div class="float">Сумма выводов: {cashoutSumm}</div>
					<div class="float">Количество: {cashoutCount}</div>
					<div class="float">Баланс: {balance_user}</div>
					<div class="float">Баланс в игре: {unresloved_summ}</div>
				</div>
				
				<div>
					<table border="0" class="newTable">
						<tr class="tabletit">
							<td>
								#Log ID
							</td>
							<td>
								Комментарий
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
		</form>
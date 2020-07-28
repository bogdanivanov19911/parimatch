		<div class="title-top">
			<h1>Настройки партнера</h1>
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
			<table width="100%" border="0" class="newTable">
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
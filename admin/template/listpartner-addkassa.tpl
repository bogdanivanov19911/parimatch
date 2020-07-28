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
						<input type="text" name="login" value="">
					</td>
				</tr>
				<tr>
					<td>
						Пароль*:
						<span></span>
					</td>
					<td>
						<input type="text" name="password" value="">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Добавить</button>
					</td>
				</tr>
			
			</table>
		</form>
		<div class="title-top">
			<h1>Добавление Домена</h1>
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
						Имя:
						<span>например: "Зеркало 55"</span>
					</td>
					<td>
						<input type="text" name="name">
					</td>
				</tr>
			
				<tr>
					<td>Ссылка*:
						<span>например: "http://saturn.bet"</span>
					</td>
					<td>
						<input type="text" name="url">
					</td>
				</tr>
			
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Добавить</button>
					</td>
				</tr>
			
			</table>
		</form>
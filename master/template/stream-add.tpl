		<div class="title-top">
			<h1>Добавление стрима</h1>
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
						Название*:
						<span>например: "D2 HUB"</span>
					</td>
					<td>
						<input type="text" name="title">
					</td>
				</tr>
			
				<tr>
					<td>
						Ссылка*:
						<span>URL на фрейм</span>
					</td>
					<td>
						<input type="text" name="src_h">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Добавить</button>
					</td>
				</tr>
			
			</table>
		</form>
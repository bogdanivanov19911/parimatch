		<div class="title-top">
			<h1>Список стримов</h1>
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
				{body}
				<tr>
					<td colspan="2" style="border-right: 0;">
						<a href="/admin.php?do=stream&option=add">
							<div class="btn btn-primary right">Добавить</div>
						</a>
					</td>
				</tr>
			</table>
		</form>
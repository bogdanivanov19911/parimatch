		<div class="title-top">
			<h1>Список партнеров</h1>
		</div>

		<div style="">{page}</div>
		
		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable">
				<tr class="tabletit">
					<td width="15px">
						ID
					</td>
					<td>
						Логин
					</td>
					<td>
						Рефералов
					</td>
					<td>
						Вкл / Выкл
					</td>
					<td align="center">
						Действие
					</td>
				</tr>
				
				{body}
			</table>
		</form>
		
		
		<script>
			$(".user_search_button").click(function() {
				user_name = $("input.user_name").val();
				
				location="/master.php?do=listpartner&login=" + user_name;
			});
		
		</script>
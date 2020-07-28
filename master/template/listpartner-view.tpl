		<div class="title-top">
			<h1>Список кассиров</h1>
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
						Баланс кассира
					</td>
					<td>
						Лимит кассы
					</td>
					<td>
						Доход кассира
					</td>
					<td align="center">
						Действие
					</td>
				</tr>
				{body}
				
				<tr>
					<td colspan="6" style="border-right: 0;">
						<a href="/master.php?do=listpartner&option=add" style="color: #fff; text-decoration: none; " class="btn btn-primary right" >Добавить кассира</a>
					</td>
				</tr>
			</table>
		</form>
		
		
		<script>
			$(".user_search_button").click(function() {
				user_name = $("input.user_name").val();
				
				location="/master.php?do=listpartner&login=" + user_name;
			});
		
		</script>
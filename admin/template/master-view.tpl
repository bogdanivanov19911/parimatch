		<div class="title-top">
			<h1>Список игроков</h1>
		</div>
		
		<div class="filter-buttons">
			<a href="/admin.php?do=listmaster&filter=1" class="buttonsNew">Текущая неделя</a>
			<a href="/admin.php?do=listmaster&filter=2" class="buttonsNew">Прошлая неделя</a>
			<a href="/admin.php?do=listmaster&filter=3" class="buttonsNew">Две недели назад</a>
			<a href="/admin.php?do=listmaster&filter=4" class="buttonsNew">Три недели назад</a>
			<a href="/admin.php?do=listmaster&filter=5" class="buttonsNew">Четыре недели назад</a>
			<a href="/admin.php?do=listmaster&filter=6" class="buttonsNew">Пять недель назад</a>
			<a href="/admin.php?do=listmaster&filter=7" class="buttonsNew">Шесть недель назад</a>
			<a href="/admin.php?do=listmaster&filter=8" class="buttonsNew">Семь недель назад</a>
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
						Вкл / Выкл
					</td>
					<td>
						Баланс
					</td>
					<td>
						В игре
					</td>
					<td>
						Открыто ставок
					</td>
					<td>
						Закрыто ставок
					</td>
					<td>
						Оборот
					</td>
					<td>
						Результат
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
				
				location="/admin.php?do=listmaster&login=" + user_name;
			});
		
		</script>
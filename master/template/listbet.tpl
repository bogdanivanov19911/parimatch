		<div class="title-top">
			<h1>Список ставок</h1>
		</div>
		
		<div class="filter-buttons">
			{filter}
			{status}
			{user_search}
		</div>
		
		<script>
		
			filter = "";
			status = "";
			
			$(".filter_select").change(function() {
				filter = $(".filter_select option:selected").val();
				status = $(".status_select option:selected").val();
				user_name = $("input.user_name").val();
				
				location="/master.php?do=listbet&filter=" + filter + "&status=" + status + "&user_name=" + user_name;
			});
			
			$(".status_select").change(function() {
				filter = $(".filter_select option:selected").val();
				status = $(".status_select option:selected").val();
				user_name = $("input.user_name").val();
				
				location="/master.php?do=listbet&filter=" + filter + "&status=" + status + "&user_name=" + user_name;
			});
			
			$(".user_search_button").click(function() {
				filter = $(".filter_select option:selected").val();
				status = $(".status_select option:selected").val();
				user_name = $("input.user_name").val();
				
				location="/master.php?do=listbet&filter=" + filter + "&status=" + status  + "&user_name=" + user_name;
			});
		
		</script>

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable">
				<tr class="tabletit">
					<td width="15px">
						#ID
					</td>
					<td>
						#UID
					</td>
					<td>
						Логин
					</td>
					<td>
						Дата
					</td>
					<td>
						Описание
					</td>
					<td>
						Коэф.
					</td>
					<td>
						Ставка
					</td>
					<td>
						Возможный выигр
					</td>
					<td>
						Результат
					</td>
					<td>
						Действие
					</td>
				</tr>
				
				{body}
			</table>
		</form>
		
		
		
		<script>
			$("div.team_winner_array").on("click",function() {
				if($(this).parent().find(".team_winner").css("display") == "block") {
					$(this).parent().find(".team_winner").css("display","none");
				} else {
					$(this).parent().find(".team_winner").css("display","block");
				}
			});
		</script>
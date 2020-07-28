		<div class="title-top">
			<h1>Горячие игры</h1>
		</div>
		
		<style>
			.jq-selectbox__select {
    background: #ffffff;
	}
		</style>

		<form enctype="multipart/form-data" action="" method="post">
		
						<div class="user_search" style="margin-left: 15px; margin-top: 8px;">
							<input type="text" class="user_name" style="width: 500px" placeholder="Название турнира" value="">
							<div class="user_search_button"></div>
						</div>
						
			<table width="100%" border="0" class="table">
				<tr>
					<td>
					</td>
					<td style="border-right: 0;">
					
						<button type="sumbit" class="btn btn-primary right" name="button">Сохранить</button>
					</td>
				</tr>
				
				{body}
				
				<tr>
					<td>
					</td>
					<td style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Сохранить</button>
					</td>
				</tr>
			</table>
			
		</form>	
		<script>
			$(".user_search_button").click(function() {
				user_name = $("input.user_name").val();
				
				location="/master.php?do=hot-tournament&search=" + user_name;
			});
		
		</script>
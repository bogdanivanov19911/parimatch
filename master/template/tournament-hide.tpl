		<div class="title-top">
			<h1>Скрытие турниров</h1>
		</div>
		
		<style>
			.jq-selectbox__select {
    background: #ffffff;
	}
		</style>
		
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
		
						<div class="user_search" style="margin-left: 15px; margin-top: 8px;">
							<input type="text" class="user_name" style="width: 500px" placeholder="Название турнира" value="">
							<div class="user_search_button"></div>
						</div>
						
			<table width="100%" border="0" class="newTable">
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
				
				location="/master.php?do=tournament-hide&search=" + user_name;
			});
		
		</script>
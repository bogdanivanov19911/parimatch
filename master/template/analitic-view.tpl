		<div class="title-top">
			<h1>Анализ пользователя</h1>
		</div>

		<form enctype="multipart/form-data" action="" method="post">
			<div>

      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
      <script type="text/javascript" src="/master/template/js/jquery.datepicker.extension.range.min.js"></script>
      <link rel="stylesheet" type="text/css" href="https://jquery-ui-bootstrap.github.io/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.3.custom.css">
	<link rel="stylesheet" type="text/css" href="/css/result-light.css">
			
			<style>
.ui-datepicker .selected-start:not(.selected-end) a,
.ui-datepicker .selected-end:not(.selected-start) a {
  background: #F3FDD5;
}

.ui-datepicker .selected.first-of-month:not(.selected-start) a {
  border-left: 2px dotted #D4E7F6;
  padding-left: 1px;
}

.ui-datepicker .selected.last-of-month:not(.selected-end) a {
  border-right: 2px dotted #D4E7F6;
  padding-right: 1px;
}
			</style>
			
			<script>
				$(function() {
			  $('#date_range').datepicker({
				range: 'period', // режим - выбор периода
				numberOfMonths: 2,
				onSelect: function(dateText, inst, extensionRange) {
					// extensionRange - объект расширения
				  $('[name=startDate]').val(extensionRange.startDateText);
				  $('[name=endDate]').val(extensionRange.endDateText);
				}
			  });

			  $('#date_range').datepicker('setDate', "y-m-d");

			  // объект расширения (хранит состояние календаря)
			  var extensionRange = $('#date_range').datepicker('widget').data('datepickerExtensionRange');
			  if(extensionRange.startDateText) $('[name=startDate]').val(extensionRange.startDateText);
			  if(extensionRange.endDateText) $('[name=endDate]').val(extensionRange.endDateText);
			  
			  var params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            var a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );
			  

				$(".search_button").click(function() {
					datestart = $('[name=startDate]').val();
					dateend = $('[name=endDate]').val();
					
					location="/master.php?do=analitic&option=view&id=" + params['id'] + "&datestart=" + datestart + "&dateend=" + dateend;
				});
			
			
			  
			});

			</script>
				
				<div style="display: inline-block;">
							<div id="date_range" style="margin: 15px"></div>
							<div class="search_button btn btn-primary btn-primaryNew" style="margin: 15px; width: 100px;">Search</div>
				</div>
				
				<div style="display: inline-block; vertical-align: top;" class="stat-inf">
					<div>Сумма депозитов: {depositsSumm}</div>
					<div>Количество: {depositsCount}</div>
					<div>Сумма выводов: {cashoutSumm}</div>
					<div>Количество: {cashoutCount}</div>
					<div>Текущий баланс: {balance_user} P</div>
					<div><a href="{userbets}" style="color: #ef4a4a;">Ставки пользователя</a></div>
					<div><a href="{useredit}" style="color: #357cd4;">Редактировать пользователя</a></div>
				</div>
				
				
				
				
				<input name="startDate" type="hidden">
				<input name="endDate" type="hidden">
					
			<hr style="border: none; height: 2px; background: #151517;">
			
			<div style="width: 49%; margin: 20px 0px; display: inline-block; text-align: center; color: #fff; font-size: 21px;">Депозиты:</div>
			
			<div style="width: 49%; margin: 20px 0px; display: inline-block; text-align: center; color: #fff; font-size: 21px;">Выводы:</div>
			
				<div style="display: inline-block; width: 46%; margin-right: 5%;">
					<table width="50%" border="0" class="newTable">
						<tr class="tabletit">
							<td>
								#UID
							</td>
							<td>
								Сумма
							</td>
							<td>
								Дата / Время
							</td>
						</tr>
						
						{body}
						
					</table>
				</div>
				
				<div style="display: inline-block; width: 46%; vertical-align: top;">
					<table width="49%" border="0" class="newTable">
						<tr class="tabletit">
							<td>
								#UID
							</td>
							<td>
								Сумма
							</td>
							<td>
								Дата / Время
							</td>
						</tr>
						
						{body2}
						
					</table>
				</div>
			</div>
		</form>
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
					numberOfMonths: 3,
					onSelect: function(dateText, inst, extensionRange) {
						// extensionRange - объект расширения
					  $('[name=startDate]').val(extensionRange.startDateText);
					  $('[name=endDate]').val(extensionRange.endDateText);
					}
				  });
				  
				  {datepickerjs}

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
					sub1 = $('[name=sub1]').val();
					sub2 = $('[name=sub2]').val();
					sub3 = $('[name=sub3]').val();
					sub4 = $('[name=sub4]').val();
					sub5 = $('[name=sub5]').val();
					streams = $('[name=streams]').val();
					
					location="/admin.php?do=listpartner&option=edit&id={id}&datestart=" + datestart + "&dateend=" + dateend + "&sub1=" + sub1 + "&sub2=" + sub2 + "&sub3=" + sub3 + "&sub4=" + sub4 + "&sub5=" + sub5 + "&streams=" + streams;
				});
			
			
			  
			});

			</script>







		<div class="title-top2">
			<h1>Статистика Партнера</h1>
		</div>

		<div style="display: inline-block;">
		
				<div id="date_range" style="margin: 8px; display: inline-block"></div>
				
				<div style="width: 250px;" class="float substyle">
					<input name="sub1" type="text" placeholder="sub1" value="{sub1}">
					<input name="sub2" type="text" placeholder="sub2" value="{sub2}">
					<input name="sub3" type="text" placeholder="sub3" value="{sub3}">
					<input name="sub4" type="text" placeholder="sub4" value="{sub4}">
					<input name="sub5" type="text" placeholder="sub5" value="{sub5}">
				</div>
				
				<div style="width: 200px; margin: 8px 0px 0px 0px;" class="float">
					<input name="startDate" type="hidden" value="{startDate}">
					<input name="endDate" type="hidden" value="{endDate}">
					
					<div style="margin: 15px 0px 0px 0px; width: 185px; background: #fb2222; padding: 8px 15px; color: #fff; font-weight: 600;">Баланс: {balance_partner}</div>
					
					<a href="/admin.php?do=listpartner&option=ref&id={id}" class="search_button btn btn-primary btn-primaryNew" style="margin: 15px 0px 0px 0px; text-decoration: none; color: #c6c6c6; width: 144px;">Пользователи</a>
					
					<a href="/admin.php?do=listpartner&option=setting&id={id}" class="search_button btn btn-primary btn-primaryNew" style="margin: 15px 0px 0px 0px; text-decoration: none; color: #c6c6c6; width: 144px;">Настройки</a>
					
					<div class="search_button btn btn-primary btn-primaryNew" style="margin: 15px 0px 0px 0px; width: 144px;">Поиск</div>
				</div>
				
				
		</div>
		
		<table width="100%" border="0" class="newTable">
			{body1}
		</table>
		
		
		
		
		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/master/template/js/jquery.datepicker.extension.range.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://jquery-ui-bootstrap.github.io/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.3.custom.css">
		<link rel="stylesheet" type="text/css" href="/css/result-light.css">
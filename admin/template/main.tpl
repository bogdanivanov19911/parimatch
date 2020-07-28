<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="ru">
	<title>Менеджер панель</title>
	<link rel="stylesheet" href="/master/template/css/index2.css?8">
	<script type="text/javascript" src="/master/template/js/jquery.js"></script>
	<link href="/master/template/css/jquery.formstyler.css?1" rel="stylesheet" />
	<script src="/master/template/js/jquery.formstyler.min.js"></script>
    <script type="text/javascript" src="/master/template/js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/master/template/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="/master/template/css/bootstrap-datetimepicker.css" />
	
	<script>
		(function($) {
			$(function() {
				$('input, select:not(#team_1,#team_2,.format_coef)').styler();
				
				$('select#team_1').styler({
					onSelectClosed: function() {
						$("#name_1").val($(this).find(".jq-selectbox__dropdown ul li.selected").html());
					}
				});
				
				$('select#team_2').styler({
					onSelectClosed: function() {
						$("#name_2").val($(this).find(".jq-selectbox__dropdown ul li.selected").html());
					}
				});
				
				$('input:checkbox').change(function() {
					if($(this).attr("checked") == "checked") {
						$(this).removeAttr("checked");
						
						id = $(this).val();
						status = 1;
						
						 $.ajax({
								type: "POST",
								url: "/admin/ajax/status.php",
								data: {"id":id,"status":status},
								cache: false,
								success: function(html){},
								error: function(){
									alert('Refresh Page');
								}
						});
					} else {
						$(this).attr("checked","checked");
						
						id = $(this).val();
						status = 0;
						
						 $.ajax({
								type: "POST",
								url: "/admin/ajax/status.php",
								data: {"id":id,"status":status},
								cache: false,
								success: function(html){},
								error: function(){
									alert('Refresh Page');
								}
						});
						
					}
				});
				

				$(".cashoutPay").on("click",function() {
							id = $(this).attr("data-id");
							status = $(this).attr("data-status");
							parent = $(this).parent();
							
							 $.ajax({
									type: "POST",
									url: "/admin/ajax/cashout.php",
									data: {"id":id,"status":status},
									cache: false,
									success: function(html){
										$(parent).html(html);
									},
									error: function(){
										alert('Refresh Page');
									}
							});
							
				});
				

				$(".cashoutPay2").on("click",function() {
							id = $(this).attr("data-id");
							status = $(this).attr("data-status");
							parent = $(this).parent();
							
							 $.ajax({
									type: "POST",
									url: "/admin/ajax/cashout-partners.php",
									data: {"id":id,"status":status},
									cache: false,
									success: function(html){
										$(parent).html(html);
									},
									error: function(){
										alert('Refresh Page');
									}
							});
							
				});
				
				 $('#datetimepicker').datetimepicker({language: 'ru', minuteStepping: 5, format: 'YYYY-MM-DD HH:mm:00'});
				
				$(".error").append('<a href="javascript:history.back()">Назад</a>');
				
				
				
				
			
				$(".newTable").on("click", "div[data-id]",function() {
						if($(this).hasClass("active") == false) {
							$(this).addClass("active");
							$("tr[data-user=" + $(this).attr("data-id") + "]").css("display","table-row");
						} else {
							$(this).removeClass("active");
							$("tr[data-user=" + $(this).attr("data-id") + "]").css("display","none");
						}
				});
				
				
					jQuery.cookie = function(name, value, options) {
						if (typeof value != 'undefined') {
							options = options || {};

							if (value === null) {
								value = '';
								options.expires = -1;
							}
							var expires = '';
							if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
								var date;
								if (typeof options.expires == 'number') {
									date = new Date();
									date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
								} else {
									date = options.expires;
								}
								expires = '; expires=' + date.toUTCString();
							}
							var path = options.path ? '; path=' + (options.path) : '';
							var domain = options.domain ? '; domain=' + (options.domain) : '';
							var secure = options.secure ? '; secure' : '';
							document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
						} else {
							var cookieValue = null;
							if (document.cookie && document.cookie != '') {
								var cookies = document.cookie.split(';');
								for (var i = 0; i < cookies.length; i++) {
									var cookie = jQuery.trim(cookies[i]);
									if (cookie.substring(0, name.length + 1) == (name + '=')) {
										cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
										break;
									}
								}
							}
							return cookieValue;
						}
					};
				
					
					

					$( ".format_coef" ).change(function() {
						$.cookie('format', $(this).val(), {expires: 360, path: "/"});
						location.reload();
					});
					
					
			});	
		})(jQuery);
	</script>
</head>
<body>
	<div class="content">
		<div class="head">
			<a href="/admin.php">
				<div class="logo">
					<img src="/image/logo.png" style="width: 221px;height: 52px;margin: 18px 0px 0px 65px;">
				</div>
			</a>
			
			{new_message}
			
			
			<a href="/?do=exit" class="exit-but">Выход</a>
		</div>
		
		<div class="leftblock">
			<ul class="leftblockmenu">
				<li><a href="/admin.php?do=listmaster"><img src="/admin/template/image/icons/nav-users.svg"> Список игроков</a></li>
				<li><a href="/admin.php?do=listpartner"><img src="/admin/template/image/icons/nav-users.svg"> Список кассиров</a></li>
				<li><a href="/admin.php?do=listbet"><img src="/admin/template/image/icons/nav-cases.svg"> Список ставок</a></li>
				<li><a href="/admin.php?do=statistic"><img src="/admin/template/image/icons/nav-pages.svg">Отчеты</a></li>
				<li><a href="/admin.php?do=cashout"><img src="/admin/template/image/icons/nav-deposit.svg">Вывод пользователей</a></li>
			</ul>
		</div>
		<div class="rightblock">
			{content}
		</div>
		
	</div>
</body>
</html>
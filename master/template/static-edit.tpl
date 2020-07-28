		<div class="title-top">
			<h1>Добавление страницы</h1>
		</div>
		
		<link href="/admin/template/css/ajax-window.css" rel="stylesheet" type="text/css" />
		<link href="/admin/template/css/iRedactor.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/admin/template/js/ajaxForm.js" /></script> 
		<script type="text/javascript" src="/admin/template/js/iRedactor.js" /></script>
		<script type="text/javascript" src="/admin/template/js/ajax-window.js" /></script>
		<script>
		$(document).ready(function() {
			var textarea = $(document).find("textarea.edit");
			var i = 0;
			jQuery.each(textarea, function() {
				i++;
				$(this).addClass("wysiwyg-" + i);
				Wysiwyg(".wysiwyg-" + i,i);
			});
		});
		</script>

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="table">
				<tr>
					<td>
						Название*:
						<span>например: "Бонусы"</span>
					</td>
					<td>
						<input type="text" name="title" value="{title}">
					</td>
				</tr>
			
				<tr>
					<td>
						Текст*:
						<span>будет содержать страница</span>
					</td>
					<td>
						<textarea style="width: 450px; height: 400px" name="text" class="edit">{text}</textarea>
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary right" name="button">Добавить</button>
					</td>
				</tr>
			
			</table>
		</form>
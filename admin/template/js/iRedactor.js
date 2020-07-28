function Wysiwyg(NewArea, number) {
	var box = document.createElement("div");
	$(box).addClass("box-redactor iRedactor-" + number);
	$(NewArea).before(box);
	var editPanel = document.createElement('ul');
	$(editPanel).addClass("edit-panel buttonPanel-" + number);
	$(".iRedactor-" + number).append(editPanel);
	editPanelListName = {"bold" : "buttonBold","italic" : "buttonItalic","underline" : "buttonUnderline","StrikeThrough" : "buttonStrike","JustifyLeft" : "buttonLeft","JustifyCenter" : "buttonCenter","JustifyRight" : "buttonRight","JustifyFull" : "buttonFull","InsertUnorderedList" : "buttonListUnorder","InsertOrderedList" : "buttonListOrder","Undo" : "buttonUndo","Redo" : "buttonRedo"};
	jQuery.each(editPanelListName, function(key,val) {
		newList = $(".buttonPanel-" + number).append($('<li></li>').attr({"class":val + " new" + val + "-" + number,"data-command":key}).append('<a></a>'));
	});
	var RedactorHeight = $(NewArea).height();
	var RedactorWidth = $(NewArea).width();
	var Redactor = document.createElement('iframe');
	$(Redactor).addClass("Editor").attr("id", "newEditor-" + number);
	$(".buttonPanel-" + number).after(Redactor);
	(wysiwyg = Redactor.contentWindow.document).open();
	wysiwyg.write('<html><style>img {max-width: 90%;}</style> <body style="word-wrap: break-word; font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif; font-size: 15px;">' + $(NewArea).text() + '</body></html>');
	wysiwyg.close();
	$(NewArea).css("display","none");
	$("#newEditor-" + number).css("height",RedactorHeight - 30 + "px");
	$(".iRedactor-" + number).css({"height":RedactorHeight + "px","width":RedactorWidth + "px"});
	$(".buttonPanel-" + number).append($("<li class=\"buttonImage newbuttonImage-" + number + "\"></li>").append('<a></a>'));
	
	$(".buttonImage").click(function() {
		imageDownload(number);
	});
	
	iframe = document.getElementById("newEditor-" + number);
	if (iframe.contentWindow) iframe = iframe.contentWindow;
	iframe.document.designMode = "on";
	
	$($("#newEditor-" + number).contents()).on("mouseup keyup mouseout click", function() {
		UpdatePanel(number);
	});
		
	function UpdatePanel(number) {
		var panel = $(".buttonPanel-" + number).find("li[data-command]");
		jQuery.each(panel, function() {
			NewButton = $(this).attr("class").split(" ");
			if (document.getElementById("newEditor-" + number).contentWindow.document.queryCommandState($("." + NewButton[1]).attr("data-command"))) {
				$(this).addClass("buttonActive").css("border","solid 1px #b5b5b5");
			} else {
				$(this).removeClass("buttonActive").css("border","solid 1px transparent");
			}
		});
		$(NewArea).text($("#newEditor-" + number).contents().find('body').html());
	}
	
	var panel = $(".buttonPanel-" + number).find("li[data-command]");
	jQuery.each(panel, function() {
		NewButton = $(this).attr("class").split(" ");
		$("." + NewButton[1]).click(function() {
			$("#newEditor-" + number).focus();
			document.getElementById("newEditor-" + number).contentWindow.document.execCommand($(this).attr("data-command"), false, true);
			UpdatePanel(number);
		});
	});
	
	function imageDownload(number) {
		$("#newEditor-" + number).focus();
		var content = '<form id="ImageDownload" enctype="multipart/form-data" action="/engine/ajax/imgload.php" method="post"><span class="UploadButton">Выберете изображение</span><input type="file" name="image" id="image" /></form><div id="loader'+ number +'"></div>';
		Ajax_Window(content, 'Загрузка изображения');
		$('#image').on('change', function() {
			$("#ImageDownload").ajaxForm({
				target: '#loader' + number,
				success:function(){
					ReadyImage = $('#loader' + number).html();
				},
				beforeSend: function(){
					$('#loader' + number).html('<img src="/engine/admin/image/loading.gif" />');
				},
				complete: function() {
					$('#loader' + number).html('');
					$("#newEditor-" + number).focus();
					document.getElementById("newEditor-" + number).contentWindow.document.execCommand('InsertHtml', false, ReadyImage);
					$('#overlay, #alertModalOuter').fadeOut(600, function() {
						$("html,body").css("overflow","auto");
						window.onmousewheel = document.onmousewheel = window.onscroll = document.onscroll = function (e) {return true;};
						$('#alertModalOuter').remove();
					});
					UpdatePanel(number);
				},
				error:function(){
					$('#loader').html('Ошибка при передачи данных! Возможно Вы пытались загрузить недопустимый формат!');
				}
			}).submit();
		});
	}
}
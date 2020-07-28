	$(function() {
		$('body').append('<div id="overlay" />');
		$('#overlay').animate({height:document.documentElement.scrollHeight});
	});
	
	function DocumentHeight() {
		var wh = document.documentElement;
		return self.innerHeight||(wh&&wh.clientHeight)||document.body.clientHeight;
	}
	
	function Ajax_Window(text, title) {
		$("html,body").css("overflow","hidden");
		window.onmousewheel = document.onmousewheel = window.onscroll = document.onscroll = function (e) {return false;};
		$('#overlay').fadeIn(800);
		$('body').append('<div id="alertModalOuter"><div id="ModalWindow"><div id="ModalTitle">'+ title +'</div><a id="ModalClose" onclick="Ajax_Close();"></a></div><div id="alertModal">'+ text +'</div> <div class="btn btn_alert_bet" style="float: right" onclick="Ajax_Close();">Закрыть</div> </div>');
		$("#alertModalOuter").fadeIn(800);
		
		$('#alertModalOuter').css('top', "25%");
		$('#alertModalOuter').css('left', "30%");
		
		
	}
	
	function Ajax_Close() {
		$('#overlay, #alertModalOuter').fadeOut(600, function() {
			$("html,body").css("overflow","auto");
			window.onmousewheel = document.onmousewheel = window.onscroll = document.onscroll = function (e) {return true;};
			$('#alertModalOuter').remove();
		});
	}
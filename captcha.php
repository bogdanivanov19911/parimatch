<?php
	$width = "120";
	$height = "60";
	$font_size = "14";
	$let_amount = rand("4","5");
	$fon_let_amount = 24; //Количество символов на фоне
	$font = "template/fonts/PTSansRegular.ttf";
	$letters = 'ABCDEFGKIJLMNOPQRSTUVWXYZ0123456789';
	$colors = array("000","100");
	$src = imagecreatetruecolor($width,$height);
	$background = imagecolorallocate($src,255,255,255);
	imagefill($src,0,0,$background);
	
	for($i=0;$i < $fon_let_amount;$i++) {
		$color = imagecolorallocatealpha($src,rand(0,255),rand(0,255),rand(0,255),100);
		$letter = $letters[rand(0, strlen($letters)-1)];
		$size = rand($font_size-2,$font_size+2);
		imagettftext($src,$size,rand(0,45),rand($width*0.1,$width-$width*0.1),rand($height*0.2,$height),$color,$font,$letter);
	}
	
	for($i=0;$i < $let_amount;$i++) {
	   $color = imagecolorallocatealpha($src,$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],rand(20,40));
		$letter = $letters[rand(0, strlen($letters)-1)];
		$size = rand($font_size*2-2,$font_size*2+2);
		$x = ($i+1)*$font_size + rand(1,5);
		$y = (($height*2)/3) + rand(0,5);
		$code[] = $letter;
		imagettftext($src,$size,rand(0,15),$x,$y,$color,$font,$letter);
	}
	
	$code = mb_strtolower(implode("",$code), 'UTF-8');
	setcookie("captcha", md5($code), time() + 3600*24);
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s",10000)." GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-Type:image/png");
	imagepng($src); 
?>
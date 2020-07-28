<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	setcookie("hash", "");
	setcookie("id", "");
	setcookie("kassaid", "");
	setcookie("kassahash", "");
	
	if(!empty($_GET['back_out'])) {
		$back = $_GET['back_out'];
	} else {
		$back = "/";
	}
	header("Location: ".$back);
	exit();
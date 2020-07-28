<?php
	define('bk', true);
	header("Content-Type: text/html; charset=utf-8");
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);

	require_once("../engine/classes/mysqli.php");
	require_once("../config.php");

	header('Location: https://'.$_SERVER['SERVER_NAME'].'/pay/fail.html');
?>
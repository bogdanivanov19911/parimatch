<?php
	header("Content-Type: text/html; charset=utf-8");
	define('bk', true);
	@ini_set('display_errors', true);
	@ini_set('html_errors', true);
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/function.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/templater.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/mysqli.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
	
	if (isset($_COOKIE['hash'])) {
		$query = $db->query('SELECT *,INET_NTOA(ip) FROM `users` WHERE `hash` = "?s" AND `id` = "?i"', $_COOKIE['hash'],$_COOKIE['id']);
		$user = $query->fetch_assoc();
		
		if($db->getAffectedRows() == 1) {
			if($user['moderator'] == 5 or $user['moderator'] == 1) {
				$logged = TRUE;
			} else {
				$logged = FALSE;
			}
		} else {
			$logged = FALSE;
		}
	} else {
		exit("ERR");
	}
	
	if($logged) {
		$dataId = preg_replace('/[^0-9]/', '', $_POST["id"]);
		
		if($_POST["status"] == 1) {
			$query = $db->query('UPDATE `users` SET `status` = "?i" WHERE `id` = "?i"', "1", $dataId);
		} elseif($_POST["status"] == 0) {
			$query = $db->query('UPDATE `users` SET `status` = "?i" WHERE `id` = "?i"', "0", $dataId);
		}
		
		echo "ok".$_POST["status"];
	} else {
		exit("ERR");
	}
	
?>
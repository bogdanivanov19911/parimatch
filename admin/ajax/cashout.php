<?php
	header("Content-Type: text/html; charset=utf-8");
	define('bk', true);
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/function.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/templater.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/mysqli.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
	
		function GetKey($lang = 10) {
			$key = '';
			$arr = array('1','2','3','4','5','6','7','8','9','0');
			
			for($i=0; $i<$lang; $i++) {
				$index = rand(0,10);
				$key .= $arr[$index];
			}
			return $key;
		}
	
	if (isset($_COOKIE['hash'])) {
		$query = $db->query('SELECT *,INET_NTOA(ip) FROM `users` WHERE `hash` = "?s" AND `id` = "?i"', $_COOKIE['hash'],$_COOKIE['id']);
		$user = $query->fetch_assoc();
		
		if($db->getAffectedRows() == 1) {
			if($user['moderator'] == 8 or $user['moderator'] == 1) {
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
		
		$query = $db->query("SELECT `id`,`id_user`,`price` FROM `cash_out` WHERE `id` = '?i'",$dataId);
		$row = $query->fetch_assoc();
		
		if($_POST["status"] == 1) {
			$info_gen = GetKey(8);
			$db->query("UPDATE `cash_out` SET `status` = '?s', `info` = '?s' WHERE `id` = '?i'",4, $info_gen ,$dataId);
			echo '<img src="/master/template/image/icons/payment-succ.svg" style="width: 30px"> <br> Одобрена';
		} elseif($_POST["status"] == 2) {
			$query = $db->query('UPDATE `cash_out` SET `status` = "?i" WHERE `id` = "?i"', "2", $dataId);
			
			$query2 = $db->query("SELECT `id`,`balance` FROM `users` WHERE `id` = '?i'", $row["id_user"]);
			$row2 = $query2->fetch_assoc();
			
			$balance = $row2["balance"] + $row["price"];
			
			$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance, $row["id_user"]);
			
			echo '<img src="/master/template/image/icons/payment-fail.svg" style="width: 30px"> <br> Отклонена';
		}
	} else {
		exit("ERR");
	}
	
?>
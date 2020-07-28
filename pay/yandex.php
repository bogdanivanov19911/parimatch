<?php
	define('bk', true);
	header("Content-Type: text/html; charset=utf-8");
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);

	header("HTTP/1.0 200 OK");
	
	require_once("./config/function.php");
	require_once("./config/mysqli.php");
	require_once("./config/config.php");
	
	date_default_timezone_set("UTC");
	
	$date = date_create(date("Y-m-d H:i:s"));
	$date_now = date_format($date,"Y-m-d H:i:s");
	
	$secret = 'Dl84rrRk9xcZ6Phui2Wrt2Ew';
	
	$r = array(
		'notification_type' => $_POST['notification_type'], // p2p-incoming / card-incoming - с кошелька / с карты
		'operation_id'      => $_POST['operation_id'],      // Идентификатор операции в истории счета получателя.
		'amount'            => $_POST['amount'],            // Сумма, которая зачислена на счет получателя.
		'withdraw_amount'   => $_POST['withdraw_amount'],   // Сумма, которая списана со счета отправителя.
		'currency'          => $_POST['intval'],            // Код валюты — всегда 643 (рубль РФ согласно ISO 4217).
		'datetime'          => $_POST['datetime'],          // Дата и время совершения перевода.
		'sender'            => $_POST['sender'],            // Для переводов из кошелька — номер счета отправителя. Для переводов с произвольной карты — параметр содержит пустую строку.
		'codepro'           => $_POST['codepro'],           // Для переводов из кошелька — перевод защищен кодом протекции. Для переводов с произвольной карты — всегда false.
		'label'             => $_POST['label'],             // Метка платежа. Если ее нет, параметр содержит пустую строку.
		'sha1_hash'         => $_POST['sha1_hash']          // SHA-1 hash параметров уведомления.
	);
	
			$exp = explode("|", $_POST["label"]);
	
			if(!empty($exp[1])) {
				$_POST["label"] = $exp[0];
				$thisComission = $exp[1];
				
				$query = $db->query("SELECT * FROM `cash_out` WHERE `id` = '?i' LIMIT 1", $thisComission);
				$row = $query->fetch_assoc();
				
				if($row["piece"] == NULL OR $row["piece"] == 0) {
					$row["piece"] = 0;
					$db->query("UPDATE `users` SET `balance` = (`balance` - ".$row["price"].") WHERE `id` = '?i'", $balance, $_POST["label"]);
				}
				
				$row["piece"] = $row["piece"] + $_POST['amount'];
				
				if($row["piece"] >= ($row["price"] / 1000) - 100) {
					$db->query("UPDATE `cash_out` SET `status` = '?i', `piece` = '?i' WHERE `id` = '?i'", "0", $row["piece"], $thisComission);
				} else {
					$db->query("UPDATE `cash_out` SET `status` = '?i', `piece` = '?i' WHERE `id` = '?i'", 4, $row["piece"], $thisComission);
				}
			} else {
				$_POST['label'] = $exp[0];
				
				$query = $db->query("SELECT `id`,`balance`,`balance_bonus` FROM `users` WHERE `id` = '?i' LIMIT 1", $_POST['label']);
				$row = $query->fetch_assoc();
				if($db->getAffectedRows() == 1) {
					
					$balance_bonus = $row['balance_bonus'] + ($_POST['amount'] * "0.1");
					
					$db->query("UPDATE `users` SET `balance_bonus` = '?i' WHERE `id` = '?i'", $balance_bonus, $_POST['label']);
					
					$balance = $row['balance'] + ($_POST['amount'] * 100);
					
					$db->query('INSERT INTO `payment`(`id_user`,`price`,`date_pay`,`status`) VALUES ("?i","?s","?s","?i")', $_POST['label'],$_POST['amount'],$date_now,"1");

					$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance, $_POST['label']);
				}
			}
?>
<?php

	if(!empty($_POST["token"])) {
		$result = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST["token"] . '&host=' . $_SERVER['HTTP_HOST']);
		$data = $result ? json_decode($result, true) : array();
		
		if(!empty($data) and !isset($data['error'])){
			$queryUL = $db->query("SELECT `id`,`login`,`email`,`password` FROM `users` WHERE `login` = '?s' OR `email` = '?s' LIMIT 1", $data['email'], $data['email']);
			$rowUL = $queryUL->fetch_assoc();
			
			$hash = mb_strimwidth($data['uid'].$data['identity'], 0, 32, "");
			$hash = md5(md5($hash));
			
			if(!empty($rowUL["id"])) { 
				if($rowUL["password"] == $hash) {
					$db->query('UPDATE `users` SET `hash` = "?s" WHERE `id` = "?i"', $hash, $rowUL["id"]);
					
					setcookie("hash", $hash, time() + (86400 * 30), "/");
					setcookie("id", $rowUL["id"], time() + (86400 * 30), "/");
					header("Location: /");
				}
			} else { // REG
				$date3 = date_create(date("Y-m-d H:i:s"));
				$date_now3 = date_format($date3,"Y-m-d H:i:s");
				$db->query('INSERT INTO `users`(`login`,`password`,`hash`,`email`,`moderator`,`register_data`) VALUES ("?s", "?s", "?s", "?s", "?i","?s")', $data['email'], $hash, $hash, $data['email'], "0",$date_now3);
				
				$queryUL = $db->query("SELECT `id`,`login`,`email` FROM `users` WHERE `login` = '?s' OR `email` = '?s' LIMIT 1", $data['email'], $data['email']);
				$rowUL = $queryUL->fetch_assoc();
				
				setcookie("hash", $hash, time() + (86400 * 30), "/");
				setcookie("id", $rowUL["id"], time() + (86400 * 30), "/");
				header("Location: /");
			}
		}
	}
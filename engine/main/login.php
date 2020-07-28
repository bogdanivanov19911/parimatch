<?php
	
	if(!empty($_POST['login'])) {
	    $login = $_POST['login'];
        $login = str_replace(array('(', ')', ' ', '-', '+'), '', $login);
		$query = $db->query("SELECT `id`,`password`,`login`,`phone`,`status` FROM `users` WHERE `login` = '?s' OR `id` = '?i' LIMIT 1", $login, (int) $login);
		$data = $query->fetch_assoc();
		
		if($db->getAffectedRows() > 0) {
			if($data['status'] == 1) {
				$errors = 'Ваш пользователь заблокирован!';
			} else {
				if($data['password'] == md5(md5($_POST['password']))) {
					$hash = md5(generateCode(rand(8, 32)));
					
					$query = $db->query('UPDATE `users` SET `hash` = "?s" WHERE `id` = "?i"', $hash, $data['id']);
					
					setcookie("hash", $hash, time() + (86400 * 30), "/");
					setcookie("id", $data["id"], time() + (86400 * 30), "/");
					header("Location: /");
				} else {
					$errors = 'Не верный пароль!';
				}
			}
		} else {
			$errors = 'Пользователь не найден!';
		}
		
		if(!empty($errors)) {
			$jsContent = 'showPopup("#login");';
		}
	}
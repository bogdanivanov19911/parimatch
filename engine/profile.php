<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	if($logged == TRUE) {
		if(isset($_POST['submit4'])) {
				$age = $_POST['day']."/".$_POST['month']."/".$_POST['year'];
				
				if(!empty($_POST["password"]) AND $_POST["password2"] == $_POST["password_verif"]) {
					
					if(md5(md5($_POST['password'])) != $user['password']) {
						$errors = 'Старый пароль введен не верно!';
					} else {
						$password = md5(md5($_POST['password2']));
						
						$db->query('UPDATE `users` SET `name` = "?s", `surname` = "?s", `email` = "?s", `age` = "?s", `phone` = "?s", `password` = "?s",`skypelogin` = "?s",`skypedata` = "?s" WHERE `id` = "?i"', $_POST['nickname'], $_POST['surname'], $_POST['email'], $age, $_POST['phone2']." ".$_POST['phone'], $password, $_POST['skypelogin'], $_POST['skypedata'], $user["id"]);
					}
				} else {
					$db->query('UPDATE `users` SET `name` = "?s", `surname` = "?s", `email` = "?s", `age` = "?s", `phone` = "?s",`skypelogin` = "?s",`skypedata` = "?s" WHERE `id` = "?i"', $_POST['nickname'], $_POST['surname'], $_POST['email'], $age,$_POST['phone2']." ".$_POST['phone'], $_POST['skypelogin'], $_POST['skypedata'], $user["id"]);
				}
		}
	}
	
?>
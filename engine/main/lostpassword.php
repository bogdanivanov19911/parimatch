<?php

	if(!empty($_POST['email6'])) {
			$query = $db->query("SELECT `id`,`login` FROM `users` WHERE `email` = '?s'", $_POST['email6']);
			if($db->getAffectedRows() == 1) {
				$row = $query->fetch_assoc();
				
				$code = md5(generateCode(rand(16, 32)));
				
					require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/class.phpmailer.php");

					$message = ' 
					<html> 
						<head> 
							<title>'. SITENAME .' | Восстановление пароля</title> 
						</head> 
						<body> 
							<p>Здравствуйте, '.$row["login"].', с вашего игрового счета поступил запрос на восстановление пароля!</p> 
							<p>Если вы не отправляли его, то просто проигнорируйте письмо.</p>
							<p>Для восстановления пароля перейдите по ссылке: <a href="https://gyzylburgut2.com/?code='.$code.'">'.$code.'</a></p> 
						</body> 
					</html>'; 
						
					$mail       = new PHPMailer();
					$mail->IsSMTP(true);            // use SMTP
					$mail->IsHTML(true);
					$mail->SMTPAuth   = true;                 // активируем SMTP аутентификацию
					$mail->CharSet = 'UTF-8';
					$mail->Priority = 1;
					$mail->Host       = MAILHOST; // SMTP хост
					$mail->Port       =  25;                    // SMTP порт
					$mail->Username   = MAILUSER;  // SMTP  имя пользователя
					$mail->Password   = MAILPASS;  // SMTP пароль
					$mail->SetFrom( MAILUSER );
					$mail->AddReplyTo( MAILUSER );
					$mail->Subject = "Восстановление пароля | ". SITENAME;
					$mail->MsgHTML($message);
					$mail->WordWrap = 50;  	
					$mail->AddAddress($_POST['email6']);
					if(!$mail->Send()) {
					   $errors = "Письмо не отправлено, повторите попытку восстановления!";
					} else {
						$db->query('INSERT INTO `lostpassword`(`code`,`user_id`) VALUES ("?s","?i")', $code,$row["id"]);
					}
			}
		
		$errors = "Перейдите по ссылке в письме, для восстановления пароля! Если письмо не пришло - подождите до 5 минут, так же проверьте папку СПАМ!";
	}
	
	if(isset($_GET['code'])) {
		$query = $db->query("SELECT `id`,`user_id` FROM `lostpassword` WHERE `code` = '?s'", $_GET['code']);
		if($db->getAffectedRows() == 1) {
			$row2 = $query->fetch_assoc();
			
			$password = generateCode(rand(7, 10));
			
			$db->query('UPDATE `users` SET `password` = "?s" WHERE `id` = "?i"', md5(md5($password)), $row2["user_id"]);
			
			$query2 = $db->query("SELECT `id`,`login`,`email`,`hash` FROM `users` WHERE `id` = '?s'", $row2["user_id"]);
			$row3 = $query2->fetch_assoc();
					require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/class.phpmailer.php");

					$message = ' 
					<html> 
						<head> 
							<title>'. SITENAME .' | Восстановление пароля</title> 
						</head> 
						<body> 
							<p>Здравствуйте, '.$row3["login"].', сохраните и запишите ваш новый пароль: <b>'.$password.'</b></p>
						</body> 
					</html>';
						
					$mail       = new PHPMailer();
					$mail->IsSMTP(true);            // use SMTP
					$mail->IsHTML(true);
					$mail->SMTPAuth   = true;                 // активируем SMTP аутентификацию
					$mail->CharSet = 'UTF-8';
					$mail->Priority = 1;
					$mail->Host       = MAILHOST; // SMTP хост
					$mail->Port       =  25;                    // SMTP порт
					$mail->Username   = MAILUSER;  // SMTP  имя пользователя
					$mail->Password   = MAILPASS;  // SMTP пароль
					$mail->SetFrom( MAILUSER );
					$mail->AddReplyTo( MAILUSER );
					$mail->Subject = "Восстановление пароля | ". SITENAME;
					$mail->MsgHTML($message);
					$mail->WordWrap = 50;  	
					$mail->AddAddress($row3['email']);
					$mail->Send();
					
			setcookie("hash", $row3["hash"]);
			setcookie("id", $row3["id"]);
			
			$db->query("DELETE FROM `lostpassword` WHERE `code` = '?s'", $_GET['code']);
		
			$errors = "Новый пароль отправлен на ваш e-mail! Если письмо не пришло - подождите до 5 минут, так же проверьте папку СПАМ!";
		} else {
			$errors = "Неверная ссылка восстановления пароля!";
		}
		
		if(!empty($errors)) {
			$jsContent = 'showPopup("#lostpassword");';
		}
	}
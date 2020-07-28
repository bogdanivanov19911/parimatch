<?php
date_default_timezone_set('Europe/Moscow');

	if(!empty($_POST['phone'])) {

	    $phone = $_POST['phone'];
        $phone = str_replace(array('(', ')', ' ', '-', '+'), '', $phone);
		$query = $db->query("SELECT `id`,`login`,`phone` FROM `users` WHERE `phone` = '?s' OR `login` = '?s'", $phone, $phone);
		$row = $query->fetch_assoc();
		
		$query5 = $db->query("SELECT `id`,`email` FROM `users` WHERE `email` = '?s'", $_POST['email2']);
		$row5 = $query5->fetch_assoc();
		
		$password = md5(md5($_POST['password2']));
		
		if($row['phone']== $phone) {
			$errors .= 'Такой телефон уже используется!';
		} elseif(($row5['email']==$_POST['email2']) && !empty($row5['email'])) {
			$errors .= 'Такой e-mail уже используется!';
		} elseif(empty($_POST['email2'])) {
			$errors .= 'E-mail обязателен!';
		} elseif(strlen($phone) > 18) {
                $errors .= 'Слишком длинный телефон!';
        }   else {
			$hash = md5(generateCode(rand(8, 32)));
			$ip = ip2long($_SERVER['REMOTE_ADDR']);
			
			$balance_rand = 0;
			
			$date3 = date_create(date("Y-m-d H:i:s"));
			$date_now3 = date_format($date3,"Y-m-d H:i:s");
			
			$db->query('INSERT INTO `users`(`login`,`phone`,`password`,`name`,`hash`,`email`,`moderator`,`register_data`, `ip`) VALUES ("?s", "?s", "?s", "?s", "?s", "?s", "?i","?s", "?i")', $phone, $phone, $password, $phone, $hash, $_POST['email2'], "0",$date_now3, $ip);
			
					$query = $db->query("SELECT `id` FROM `users` WHERE `login` = '?s' LIMIT 1", $phone);
					$row2 = $query->fetch_assoc();
					
					if(!empty($_COOKIE["stt"]) AND !empty($_COOKIE["str"])) {
						$query3 = $db->query("SELECT `id`,`user_id`,`registration_pb`,`postback`,`source_id` FROM `streams` WHERE `id` = '?i' LIMIT 1", $_COOKIE["str"]);
						$row3 = $query3->fetch_assoc();
						
						$query4 = $db->query('SELECT * FROM `stats` WHERE `id` = "?i" LIMIT 1',$_COOKIE["stt"]);
						$row4 = $query4->fetch_assoc();
						
						if($row3["registration_pb"] == 1) {
							$url = $row3["postback"];
							$url = str_replace("{id}",$row2['id'],$url);
							$url = str_replace("{status}","register",$url);
							$url = str_replace("{date}", strtotime(date("Y-m-d H:i:s")) ,$url);
							$url = str_replace("{sub1}",$row4["s1"],$url);
							$url = str_replace("{sub2}",$row4["s2"],$url);
							$url = str_replace("{sub3}",$row4["s3"],$url);
							$url = str_replace("{sub4}",$row4["s4"],$url);
							$url = str_replace("{sub5}",$row4["s5"],$url);
							$url = str_replace("{stream}",$row3['id'],$url);
							$url = str_replace("{source}",$row3['source_id'],$url);
							$url = str_replace("{fdp}","0",$url);
							
							file_get_contents($url);
						}
						
						$db->query('INSERT INTO `referrals`(`referrals_id`,`user_id`,`stream_id`,`stat_id`,`user_ip`) VALUES ("?i", "?i", "?i", "?i", "?i")', $row3["user_id"], $row2['id'], $_COOKIE["str"], $_COOKIE["stt"], $ip);
					} else {
                        $query = $db->query("SELECT * FROM `tmp_user` WHERE `ip` = '?s' LIMIT 1", $ip);
                        $res = $query->fetch_assoc();
                        if(!empty($res['ip'])) {
                            $query3 = $db->query("SELECT `id`,`user_id`,`registration_pb`,`postback`,`source_id` FROM `streams` WHERE `id` = '?i' LIMIT 1", $res['stream_id']);
                            $row3 = $query3->fetch_assoc();

                            $db->query('INSERT INTO `referrals`(`referrals_id`,`user_id`,`stream_id`,`stat_id`,`user_ip`) VALUES ("?i", "?i", "?i", "?i", "?i")', $row3["user_id"], $row2['id'], $res['stream_id'], $res['stat_id'], $ip);
                            }

                        }

			setcookie("hash", $hash, time() + 3600*24*30*12, "/");
			setcookie("id", $row2['id'], time() + 3600*24*30*12, "/");
			setcookie("registration", "1", time() + 3600*24*30*12, "/");
			header("Location: https://".$_SERVER['SERVER_NAME']);
		}

		if(!empty($errors)) {
			$jsContent = 'showPopup("#registration");';
		}
	}
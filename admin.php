<?php
	define('bk', true);
	header("Content-Type: text/html; charset=utf-8");
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);

	require_once("./engine/classes/templater.php");
	require_once("./engine/classes/function2.php");
	require_once("./engine/classes/mysqli.php");
	require_once("./config.php");
	
	date_default_timezone_set('Europe/Moscow');
	
	$date_now = date("Y-m-d H:i:s");
	$ip = ip2long($_SERVER['REMOTE_ADDR']);
	$error = NULL;
	
	if(empty($_COOKIE['format'])) {
		$_COOKIE['format'] = 1;
		setcookie("format", 1);
	}

	if($_COOKIE['format'] == 1 OR $_COOKIE['format'] == 2) {
		
	} else {
		$_COOKIE['format'] = 1;
		setcookie("format", 1);
	}
	
	if (isset($_COOKIE['hash'])) {
		$query = $db->query('SELECT *,INET_NTOA(ip) FROM `users` WHERE `hash` = "?s" AND `id` = "?i"', $_COOKIE['hash'],$_COOKIE['id']);
		$user = $query->fetch_assoc();
		
		if($db->getAffectedRows() == 1) {
			if($user['moderator'] == 8) {
				$logged = TRUE;
			} else {
				$logged = FALSE;
			}
		} else {
			$logged = FALSE;
		}
	} else {
		function generateCode($length=6) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;
			while (strlen($code) < $length) {
				$code .= $chars[mt_rand(0,$clen)];
			}
			return $code;
		}
		if(isset($_POST['submit'])) {
				$query = $db->query('SELECT * FROM `users` WHERE `login` = "?s" AND `moderator` = "8"', $_POST['login']);
				$data = $query->fetch_assoc();
				$operations = $db->getAffectedRows();
				
				if($operations == 1) {
					if($data['password'] == md5(md5($_POST['password']))) {
						$hash = md5(generateCode(rand(8, 32)));
						
						$query = $db->query('UPDATE `users` SET `hash` = "?s" WHERE `id` = "?i"', $hash, $data['id']);
						
						setcookie("hash", $hash);
						setcookie("id", $data["id"]);
						header("Location: /admin.php"); exit();
					} else {
						$error = "Неверный пароль!";
					}
				} else {
					$error = "Пользователь не найден!";
				}
		}
	}
	
	function factor_back($factor, $type = "echo") {
		if($type == "echo") {
			$factor = $factor / 100;
		} else {
			$factor = $factor * 100;
		}
		
		return $factor;
	}
	
	
	if($logged) {
	
		switch($_GET["do"]) {
			case "main" :
				include 'admin/main.php';
				break;
				
			case "listmaster" :
				include 'admin/listmaster.php';
				break;
				
			case "listbet" :
				include 'admin/listbet.php';
				break;
				
			case "betcontrol" :
				include 'admin/betcontrol.php';
				break;
				
			case "flags" :
				include 'admin/flags.php';
				break;
				
			case "hot-tournament" :
				include 'admin/hot-tournament.php';
				break;
				
			case "tournament-hide" :
				include 'admin/tournament-hide.php';
				break;
				
			case "analitic" :
				include 'admin/analitic.php';
				break;
				
			case "statistic" :
				include 'admin/statistic.php';
				break;
			
			case "cashout" :
				include 'admin/cashout.php';
				break;
			
			case "cashout-partners" :
				include 'admin/cashout-partners.php';
				break;
			
			case "source" :
				include 'admin/source.php';
				break;
			
			case "logs" :
				include 'admin/logs.php';
				break;
				
			case "mirrors" :
				include 'admin/mirrors.php';
				break;
				
			case "mirrors-redirect" :
				include 'admin/mirrors-redirect.php';
				break;
				
			case "listpartner" :
				include 'admin/listpartner.php';
				break;
				
			case "static" :
				include 'admin/static.php';
				break;
				
			default:
				if($_SERVER['REQUEST_URI'] == '/admin.php' or !empty($_GET["page"])) {
					include 'admin/main.php';
				} else {
					header('Location: /admin.php');
				}
		}
		
	
		$tpl = new template('admin/template/main.tpl');
		$tpl->set('{content}', $content);
		
		echo $tpl->parse();
	} else {
		setcookie("hash", "");
		setcookie("id", "");
		
		$tpl = new template('admin/template/login.tpl');
		$tpl->set('{error}', $error);
		echo $tpl->parse();
	}
?>
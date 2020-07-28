<?php
	define('bk', true);
	header("Content-Type: text/html; charset=utf-8");
	@ini_set('display_errors', false);
	@ini_set('html_errors', true);

	ini_set('memory_limit', '-1');

	require_once("./engine/classes/templater.php");
	require_once("./engine/classes/function.php");
	require_once("./engine/classes/mysqli.php");
	require_once("./config.php");

	
	$ip = ip2long($_SERVER['REMOTE_ADDR']);

	function generateCode($length=6) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;
		while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,$clen)];
		}
		return $code;
	}
	
	$error = NULL;
	
	date_default_timezone_set("UTC");

	
	if(isset($_GET["str"])) {
		setcookie("str", $_GET["str"], time()+60*60*24*30, "/");
	}
	
	if(isset($_GET["stt"])) {
		setcookie("stt", $_GET["stt"], time()+60*60*24*30, "/");
	}
	
	require_once("./engine/main/authentication.php");
	require_once("./engine/main/login.php");
	require_once("./engine/main/registration.php");
	require_once("./engine/main/lostpassword.php");
	
	$date = date_create(date("Y-m-d H:i:s"));
	
	if(empty($_COOKIE['hour'])) {
		$_COOKIE['hour'] = "+0";
		setcookie("hour", $_COOKIE['hour']);
	} else {
		$_COOKIE['hour'] = preg_replace('/[^0-9-+]/', '', $_COOKIE['hour']);
	}
	
	$_COOKIE['hour'] = "+2";
	
	$date->modify("+".$_COOKIE['hour']." hour");
	$date_now = date_format($date,"Y-m-d H:i:s");
	$time_now = date_format($date,"H:i");
	
	$db->setTypeMode(Database_Mysql::MODE_TRANSFORM);
	
	
		if(empty($user["login"])) {
			$user["login"] = "";
		}
		
		$user_login = $user["login"];
		
		global $user_login;
		
		if(!empty($_GET['do'])) {
			$do = $_GET['do'];
		} else {
			$do = "";
		}
		
		global $do;
		
		if(!empty($_GET['page'])) {
			$page = $_GET['page'];
		}
		
		switch($do) {
			case "main" :
				include 'engine/main.php';
				break;
				
			case "line" :
				include 'engine/line.php';
				break;
				
			case "live" :
				include 'engine/live.php';
				break;
				
			case "static" :
				include 'engine/static.php';
				break;

			case "history-bets" :
				include 'engine/history-bets.php';
				break;
				
			case "history-cash" :
				include 'engine/history-cash.php';
				break;
				
			case "cashout" :
				include 'engine/cashout.php';
				break;
				
			case "profile" :
				include 'engine/profile.php';
				break;
				
			case "deposit" :
				include 'engine/deposit.php';
				break;
				
			case "exit" :
				include 'engine/exit.php';
				break;
				
			case "event" :
				include 'engine/event.php';
				break;
				
			default:
				if($_SERVER['REQUEST_URI'] == '/' OR (isset($_GET["str"]) AND isset($_GET["stt"])) OR isset($_GET["code"])) {
					include 'engine/main.php';
				} else {
					include 'engine/main.php';
				}
		}
		
		if(!isset($user["login"])) $user["login"] = "Гость";
		if(!isset($user["surname"])) $user["surname"] = "";
		if(!isset($user["balance"])) $user["balance"] = "0";
		if(!isset($user["id"])) $user["id"] = "-";
		if ($_SERVER['REQUEST_URI'] == '/'){
			$slider = '
		 			<div class="col-slider" style="margin-bottom: -3px;">
						<img width="100%" src="/images/slider/banner1000rub.jpg">
						<img src="/image/signs.png" width="20" class="close-slider" id="close-slider">
					</div>
		 ';
		} else $slider = null;
		if ($_SERVER['REQUEST_URI'] == '/'){
			$add = '
		 			<div class="col-add">
						<img width="100%" src="/images/slider/add.jpg">
						<img src="/image/signs.png" width="20" class="close-add" id="close-add">
					</div>
		 ';
		} else $add = null;
		$tpl = new template('template/main.tpl');
		$tpl->set('{content}', $body);
		$tpl->set('{slider}', $slider);
		$tpl->set('{add}', $add);
		$tpl->set('{tourngamesort}', $tourGameSort);
		if(empty($user["name"])) $user["name"] = $user["login"];
		$tpl->set('{name}', $user["name"]);
		$vip = ($user["vip"]) ? "vip" : "disable";
		$tpl->set('{vip}', $vip);
		$icon = ($user["vip"]) ? "icon-ai-vip-label" : "icon-AccountCircle";
		$tpl->set('{icon}', $icon);
		$cashout = ($user["ss"])
			? '<div id="cashout-disable"><a href="/?do=cashout" class="sidebar-list__item disable"><i class="icon icon-sidebar-withdraw"></i> Вывод средств </a></div>'
			: '<a href="/?do=cashout" class="sidebar-list__item "><i class="icon icon-sidebar-withdraw"></i> Вывод средств </a>';
		$tpl->set('{cashout}', $cashout);
		$tpl->set('{surname}', $user["surname"]);
		$tpl->set('{balance}', BK::factor($user["balance"]));
		$tpl->set('{win_balance}', BK::factor($user["win_balance"]));
		$tpl->set('{full_balance}', BK::factor($user["win_balance"]+$user["balance"]));
		(isset($user["unresolved"])) ? $user["unresolved"] : $user["unresolved"] = "000";
		$tpl->set('{balance_unresolved}', BK::factor($user["unresolved"]));
		$tpl->set('{time}', $time_now);
		$tpl->set('{UTC}', $_COOKIE['hour']);
		(isset($frames)) ? $frames : $frames = "";
		$tpl->set('{frames}', $frames);
		if(!empty($errors)) {
			$tpl->set('{errors}', "<div class='error-login'>".$errors."</div>");
		} else {
			$tpl->set('{errors}', "");
		}
		$tpl->set('{id}', $user["id"]);
		
		// 1 БЛОК
		
		$date_events = date("Y-m-d H:i:s");
		$gameArray = array();
		
		$queryGames = $db->query('SELECT * FROM `games` ORDER BY `sorting`');
		$rowGames = $queryGames->fetch_assoc_array();
		
		foreach($rowGames as $key => $value) {
			$gameArray[$value["id"]]["name"] = $value["name"];
		}
		
		$queryTourn = $db->query('SELECT * FROM `tournaments` WHERE `name` IS NOT NULL AND `hide` = 0 AND `sorting` != 0 ORDER BY `sorting`, `name`');
		$rowTourn = $queryTourn->fetch_assoc_array();
		
		foreach($rowTourn as $key2 => $value2) {
			$gameArray[$value2["game"]]["tournaments"][$value2["id"]]["name"] = $value2["name"];
			$gameArray[$value2["game"]]["tournaments"][$value2["id"]]["flags"] = $value2["flags"];
		}
		
		$queryEvent = $db->query('SELECT `id`,`game_id`,`tournament_id` FROM `events` WHERE `result` IS NULL AND `is_live` = 0 AND `time_start` >= "'.$date_events.'"');
		$rowEvent = $queryEvent->fetch_assoc_array();
		
		foreach($rowEvent as $key3 => $value3) {
			if(!empty($gameArray[$value3["game_id"]]["tournaments"][$value3["tournament_id"]]["name"])) {
				$gameArray[$value3["game_id"]]["tournaments"][$value3["tournament_id"]]["events"] = $value3["id"];
			}
		}
		
		foreach($gameArray as $key4 => $value4) {
			if(!empty($value4["tournaments"])) {
				foreach($value4["tournaments"] as $key5 => $value5) {
					if(!empty($value5["events"])) {
						$value5["name"] = str_replace($value4["name"].". ", "", $value5["name"]);
						
						$tournament_block .= '
						<div>
							<prematch-line-championship class="prematch-block prematch-block_line">
								<a class="prematch-block__content">
									<div class="prematch-block-text" onclick="searchLeague('.$key5.');">
										<h4 class="prematch-block-text__title"> '.$value5["name"].' </h4>
										<div class="prematch-block-text__event"></div>
									</div>
								</a>
							</prematch-line-championship>
						</div>';
					}
				}
			}
			
			if(!empty($tournament_block)) {

				$game_block .= '
						  
<prematch-line-sport class="prematch-block" data-game="'.$key4.'">
	<a class="prematch-block__content" href="#">
		<span class="prematch-block__icon">
			<i class="sporticon sporticon-F"></i>
		</span>
		<div class="prematch-block-text">
			<h4 class="prematch-block-text__title"> '.$value4["name"].' </h4>
			<div class="prematch-block-text__event"></div>
		</div>
	</a>
</prematch-line-sport>
						  
						  .$tournament_block.
						  ';
			}
			
			unset($tournament_block);
		}
		
		$block_1 = $game_block;
	
		$tpl->set('{block_1}', $block_1);
		
		$content_header .= '';
	
		if(empty($_COOKIE['bonusshow'])) {
			setcookie("bonusshow", 1);
		}
		
		$tpl->set('{content_header}', $content_header);
		$tpl->set('{modalDialogs}', $modalDialogs);
		$tpl->set('{jsContent}', $jsContent);
		
		
		require_once 'engine/classes/mobile_detect.php';
		
		$detect = new Mobile_Detect;
		
		if(empty($user["balance_bonus"])) {
			$user["balance_bonus"] = 0;
		}
		
	if ($detect->isMobile()) {
		if($do == "profile" OR $do == "deposit") {
			$tpl->set('{code_supp2}', $code_supp);
		} else {
			$tpl->set('{code_supp2}', "");
		}
		$tpl->set('{style_head}', '');
	} else {
		$tpl->set('{code_supp2}', $code_supp);
	}
	
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
		$protokol = "https://";
	} else {
		$protokol = "http://";
	}
	
	$tpl->set('{server_name}', $protokol.$_SERVER['SERVER_NAME']);
	$tpl->set('{sitename}', SITENAME );
	$tpl->set('{bonus_balance}', $user["balance_bonus"] / 100 );
	$tpl->set('{IDEV}', $_GET['id']);
	
	$bidth = explode("/",$user['age']);
	$tpl->set('{name}', $user['name']);
	$tpl->set('{surname}', $user['surname']);
	$tpl->set('{email}', $user['email']);
	$tpl->set('{phone}', $user['phone']);
	$tpl->set('{date_day}', $bidth[0]);
	$tpl->set('{date_month}', $bidth[1]);
	$tpl->set('{date_year}', $bidth[2]);
	$tpl->set('{surname2}', $user['skypelogin']);
		
		
	echo $tpl->parse();
?>

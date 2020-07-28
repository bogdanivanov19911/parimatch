<?php
	header("Content-Type: text/html; charset=utf-8");
	define('bk', true);
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);
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
		
		$query = $db->query("SELECT `id`,`id_user`,`system`,`price`,`number` FROM `cash_out` WHERE `id` = '?i'",$dataId);
		$row = $query->fetch_assoc();
		
		if($_POST["status"] == 1) {
			$query = $db->query('UPDATE `cash_out` SET `status` = "?i" WHERE `id` = "?i"', "1", $dataId);
			
			echo '<img src="/master/template/image/icons/payment-succ.svg" style="width: 30px"> <br> Выплачена';
		} elseif($_POST["status"] == 3) {
					$wallet_id = 'F107049878';
					$user_wallet = $row['number'];
					$api_key = 'F96CF1C99466F5A55CD2DBB385A595A0';
					$amount = $row['price']/100;
					
					$system_cashout = str_replace(' ','',trim(preg_replace('/\s{2,}/', ' ', $row['system'])));
					
					if($system_cashout == 'Банковскаякарта') {
						$cashout_payway = 94;
					} elseif($system_cashout == 'QIWI-кошелек') {
						$cashout_payway = 63;
					} elseif($system_cashout == 'WebMoney') {
						$cashout_payway = 2;
					} elseif($system_cashout == 'МегаФон') {
						$cashout_payway = 82;
					} elseif($system_cashout == 'Яндекс.Деньги') {
						$cashout_payway = 45;
					} elseif($system_cashout == 'PayPal') {
						$cashout_payway = 70;
					}
					
                   $data = array(
                        'wallet_id' => $wallet_id,
                        'purse' => $user_wallet,
                        'amount' => $amount,
                        'desc' => 'Pay by BetOn',
                        'currency' => $cashout_payway,
                        'sign'=>md5($wallet_id.$cashout_payway.$amount.$user_wallet.$api_key),
                        'action' => 'cashout',
                    );
					
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $result = trim(curl_exec($ch));
                    $c_errors = curl_error($ch);
                    curl_close($ch);
					
					$res = json_decode($result,true);
					
				if($res['desc'] == 'Payment send' AND !empty($res['data']['payment_id'])) {
					$query = $db->query('UPDATE `cash_out` SET `status` = "?i", `data` = "?s" WHERE `id` = "?i"', "9", $res['data']['payment_id'], $dataId);
					echo '<img src="/master/template/image/icons/payment-succ.svg" style="width: 30px"> <br> Заявка успешно оформленна в кассе';
				} else {
					print_r('Ошибка кассы: '.$res['desc']);
				}
				
				
				
		} elseif($_POST["status"] == 2) {
			$query = $db->query('UPDATE `cash_out` SET `status` = "?i" WHERE `id` = "?i"', "2", $dataId);
			
			$query2 = $db->query("SELECT `id`,`win_balance` FROM `users` WHERE `id` = '?i'", $row["id_user"]);
			$row2 = $query2->fetch_assoc();
			
			$win_balance = $row2["win_balance"] + $row["price"];
			
			$db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $win_balance, $row["id_user"]);
			
			echo '<img src="/master/template/image/icons/payment-fail.svg" style="width: 30px"> <br> Отклонена';
		}
	} else {
		exit("ERR");
	}
	
?>
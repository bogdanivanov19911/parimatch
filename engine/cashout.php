<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	
	if($logged) {
		function GetKey($lang = 10) {
			$key = '';
			$arr = array('1','2','3','4','5','6','7','8','9','0');
			
			for($i=0; $i<$lang; $i++) {
				$index = rand(0,35);
				$key .= $arr[$index];
			}
			return $key;
		}
		
		if(!empty($_POST['cashout'])) {
			if(!empty($_POST["requisites"])) {
				if((($user["win_balance"]/100) >= $_POST['cashout']) and ($_POST['cashout'] >= 5000)) {
					$dc_py_dat = json_decode($user["payment_data"],true);
					
//					if($dc_py_dat['count'] < 3) {
//						$error = "<div class='error-login' style='margin-bottom: 35px;'>Для вывода средств вам необходимо отыграть бонус! <a style='color: #fff;padding: 5px;background: #4f6aa5;margin-left: 12px;' href='/'>Читать правила бонуса</a></div>";
//
//						$tpl = new template('template/cashout.tpl');
//						$tpl->set('{error}', $error);
//						$tpl->set('{user}', $user["id"]);
//						$tpl->set('{pay_id}', GetKey(10));
//						$body = $tpl->parse();
//					} else {
						$date3 = date_create(date("Y-m-d H:i:s"));
						$date_now3 = date_format($date3,"Y-m-d H:i:s");
						$dataPrice = $_POST['cashout'] * 100;
						$db->query("UPDATE `users` SET `win_balance` = `win_balance` - ".$dataPrice." WHERE `hash` = '?s' AND `id` = '?i'", $_COOKIE['hash'],$user["id"]);
						$db->query("INSERT INTO `cash_out`(`id_user`,`price`,`system`,`number`,`date`,`status`,`info`) VALUES('?i','?i','?s','?s','?s','?i','?s')",$user["id"],$dataPrice,$_POST["type_cash"],$_POST["requisites"],$date_now3,"0","");
						
						$db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
						$user["id"],$dataPrice / 100 , "Вывод средств. Сумма: -".$dataPrice / 100 , date("Y-m-d H:i:s") , 2);
						
						header('Location: /?do=history-cash&cashout=true');
//					}
				} elseif((($user["win_balance"]/100) <= $_POST['cashout']) and ($_POST['cashout'] >= 5000)) {
					
					$error = "<div class='error-login' style='margin-bottom: 35px;'>Недостаточно средств на счете!</div>";
				
					$tpl = new template('template/cashout.tpl');
					$tpl->set('{error}', $error);
					$tpl->set('{user}', $user["id"]);
					$tpl->set('{pay_id}', GetKey(10));
					$body = $tpl->parse();
				} else {
                    $error = "<div class='error-login' style='margin-bottom: 35px;'>Минимальная сумма вывода 5000!</div>";

                    $tpl = new template('template/cashout.tpl');
                    $tpl->set('{error}', $error);
                    $tpl->set('{user}', $user["id"]);
                    $tpl->set('{pay_id}', GetKey(10));
                    $body = $tpl->parse();
                }
			} else {
					$error = "			<div class='error-login' style='margin-bottom: 35px;'>
						Заполните все поля для вывода!
					</div>";

					$tpl = new template('template/cashout.tpl');
					$tpl->set('{error}', $error);
					$tpl->set('{user}', $user["id"]);
					$tpl->set('{pay_id}', GetKey(10));
					$body = $tpl->parse();
			}
		} else {
			$tpl = new template('template/cashout.tpl');
			$tpl->set('{error}', "");
			$tpl->set('{user}', $user["id"]);
			$tpl->set('{pay_id}', GetKey(10));
			$body = $tpl->parse();
		}
	} else {
		$line_bets = "<div class='error-login' style='margin-bottom: 35px;'>Зарегистрируйтесь, чтобы просмотреть страницу!</div>";
	
		$body = $line_bets;
	}
	
?>
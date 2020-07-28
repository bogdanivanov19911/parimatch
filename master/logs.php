<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	$date_events = date("Y-m-d H:i:s");
	
	if(!empty($_GET["id"])) {
		
		$query11 = $db->query('SELECT * FROM `users` WHERE `id` = "?i" LIMIT 1',$_GET["id"]);
		$row11 = $query11->fetch_assoc();
		
		$queryD = $db->query("SELECT * FROM `payment` WHERE `id_user` = '?i' AND `status` = 1 AND `hide` IS NULL", $_GET["id"]);
		$rowD = $queryD->fetch_assoc_array();
		
		$d=0;
		$summAll = 0;
		foreach($rowD as $keyD => $valueD) {
			$summAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `id_user` = '?i' AND `status` = 1", $_GET["id"]);
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		$summAllW = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		$queryU = $db->query("SELECT `id`,`user_id`,`price` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` = 0", $_GET["id"]);
		$rowU = $queryU->fetch_assoc_array();
		
		$u = 0;
		$unresloved_summ = 0;
		foreach($rowU as $keyU => $valueU) {
			$unresloved_summ += $valueU["price"] / 100;
			$u++;
		}
		
		$query25 = $db->query('SELECT * FROM `logs_stat` WHERE `user_id` = "?i"', $row11["id"]);
		$row25 = $query25->fetch_assoc_array();
		
		if(!empty($row25[0])) {
			foreach($row25 as $key25 => $value25) {
				$body .= '
							<tr>
								<td>
									'.$value25["id"].'
								</td>
								<td>
									'.$value25["comment"].'
								</td>
								<td>
									'.$value25["amount"].'
								</td>
								<td>
									'.$value25["date"].'
								</td>
							</tr>';
			}
			
			$tpl = new template('master/template/logs-view.tpl');
			$tpl->set('{body}', $body);
			$tpl->set('{depositsCount}', $d);
			$tpl->set('{depositsSumm}', $summAll);
			$tpl->set('{cashoutCount}', $w);
			$tpl->set('{cashoutSumm}', $summAllW);
			$tpl->set('{balance_user}', $row11["balance"] / 100);
			$tpl->set('{unresloved_summ}', $unresloved_summ);
			$content = $tpl->parse();
		} else {
			$content = '<div class="error">У этого пользователя отсутствуют логи!</div>';
		}
	} else {
		$content = '<div class="error">Пользователь не найден!</div>';
	}
?>
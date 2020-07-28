<?php
	if(!defined('bk')) die('Hacking Attempt!');

	if($logged) {
		if($_GET['cashout'] == true) {
			$query = $db->query("SELECT COUNT(id) FROM `cash_out` WHERE `id_user` = '?i'", $user["id"]);
			$row = $query->fetch_assoc();
			$number = $row["COUNT(id)"];
		
			if($number == 0) {
				$errors = 'Заявок на вывод не найдено!';
			} else {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`system`,`info`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out` WHERE `id_user` = '?i' ORDER BY `id` DESC", $user["id"]);
			
				for($i = 1; $i <= $number; $i++) {
					$row = $query->fetch_assoc();
					
					if($row["status"] == 0) {
						$status = '<span class="c-orange">В обработке</span>';
					} elseif($row["status"] == 1) {
						$status = '<span class="c-green">Выплачено</span>';
					} elseif($row["status"] == 2) {
						$status = '<span class="c-red">Отклонено</span>';
					} elseif($row["status"] == 4) {
						$status = '<span class="c-orange">Обратитесь к кассиру #ID '.$row["system"].'</span>
						<button type="button" class="btn btn-acid-yellow getCodeW" data-code="'.$row["info"].'" style="padding: 5px 30px; display: block; margin-top: 10px; width: auto;"> КОД </button>';
					}
					
					$history .= ' <tr>
										<td>'.$row["date2"].'</td>
										<td>'. $row["price"] / 100 .'</td>
										<td>'.$status.'</td>
									</tr>';
				}
				
					
				$tpl = new template('template/history-cash-out.tpl');
				$tpl->set('{history}', $history);
				$line_bets = $tpl->parse()."\n\n";
				
				
				
			}
		} else {
			$query = $db->query("SELECT COUNT(id) FROM `payment` WHERE `id_user` = '?i'", $user["id"]);
			$row = $query->fetch_assoc();
			$number = $row["COUNT(id)"];
		
			if($number == 0) {
				$line_bets = '<div class="no-bets">Пополнений не найдено!</div>';
			} else {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`date_pay`,`status`,DATE_FORMAT(date_pay,'%d/%m/%y %H:%i') as `date_pay2` FROM `payment` WHERE `id_user` = '?i' ORDER BY `id` DESC", $user["id"]);
			
				for($i = 1; $i <= $number; $i++) {
					$row = $query->fetch_assoc();
					$tple = new template('template/line-history-cash.tpl');
					$tple->set('{date}', $row["date_pay2"]);
					$tple->set('{price}', $row["price"]);
					$tple->set('{date}', $row["date_pay2"]);
					if($row["status"] == 1) {
						$tple->set('{status}', '<span class="c-green">Пополнено</span>');
					} else {
						$tple->set('{status}', "-");
					}
					$history .= $tple->parse()."\n\n";
				}
				
				$tpl = new template('template/history-cash.tpl');
				$tpl->set('{history}', $history);
				$line_bets = $tpl->parse()."\n\n";
			}
		}
	} else {
		$line_bets = '<div class="no-bets">Ошибка!</div>';
	}
	
	$body = $line_bets;
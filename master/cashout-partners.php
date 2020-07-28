<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$query = $db->query("SELECT COUNT(id) FROM `cash_out_agent` WHERE `status` = 0");
	$row = $query->fetch_assoc();
	$number = $row["COUNT(id)"];
	
	
	if(empty($_GET["id"])) {
		if($number == 0) {
			$content = '<div class="error">Все выплачено! Заявок нет.</div>';
		} else {
			$query = $db->query("SELECT `id`,`id_user`,`price`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out_agent` WHERE `status` = 0 ORDER BY `id` DESC");
		
			for($i = 1; $i <= $number; $i++) {
				$row = $query->fetch_assoc();
				
				$query2 = $db->query('SELECT `login` FROM `users` WHERE `id` = "?i"', $row['id_user']);
				$row2 = $query2->fetch_assoc();
				
				$price = $row["price"]/100;
				
					$body .= '
							<tr>
								<td>
									'.$row["id"].'
								</td>
								<td>
									<input type="text" class="inp" value="'.$row["number"].'" onclick="select();" readonly="readonly">
								</td>
								<td>
									'.$row2["login"].'
									
									<div class="cashoutInf">
										<a href="/master.php?do=listmaster&option=edit&id='.$row["id_user"].'">Профиль</a>
									</div>
									'.$comment.'
								</td>
								<td>
									'.$price.'
								</td>
								<td>
									'.$row["date"].'
								</td>
								<td>
									<div class="cashoutBlock">
										<div class="btn btn-primary right cashoutPay2" data-id="'.$row["id"].'" data-status="1">Выплачено</div>
										
										<div class="btn btn-primary right cashoutPay2" data-id="'.$row["id"].'" data-status="2">Отклонить</div>
									</div>
								</td>
							</tr>
					';
			}
			
			$tpl = new template('master/template/cashout-view-partner.tpl');
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		}
	} else {
		if(empty($_GET["back"])) {
			$db->query("UPDATE `cash_out_agent` SET `status` = '?s' WHERE `id` = '?i'",1,$_GET["id"]);
			header('Location: /master.php?do=cashout-partners');
		} else {
			$db->query("UPDATE `cash_out_agent` SET `status` = '?s' WHERE `id` = '?i'",2,$_GET["id"]);
			
			$query = $db->query("SELECT `id`,`id_user`,`price` FROM `cash_out_agent` WHERE `id` = '?i'",$_GET["id"]);
			$row = $query->fetch_assoc();
			
			$query2 = $db->query("SELECT `balance_agent` FROM `users` WHERE `id` = '?i'", $row["id_user"]);
			$row2 = $query2->fetch_assoc();
			
			$balance = $row2["balance_agent"] + $row["price"];
			
			$db->query("UPDATE `users` SET `balance_agent` = '?i' WHERE `id` = '?i'", $balance, $row["id_user"]);
			header('Location: /master.php?do=cashout-partners');
		}
	}
?>
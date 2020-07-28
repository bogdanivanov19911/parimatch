<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
		function GetKey($lang = 10) {
			$key = '';
			$arr = array('1','2','3','4','5','6','7','8','9','0');
			
			for($i=0; $i<$lang; $i++) {
				$index = rand(0,10);
				$key .= $arr[$index];
			}
			return $key;
		}
	
	$query = $db->query("SELECT COUNT(id) FROM `cash_out` WHERE `status` = 0");
	$row = $query->fetch_assoc();
	$number = $row["COUNT(id)"];
	
	$query2 = $db->query("SELECT COUNT(id) FROM `cash_out` LIMIT 50");
	$row2 = $query2->fetch_assoc();
	$number2 = $row2["COUNT(id)"];
	
	if(empty($_GET["id"])) {
		if($number == 0 AND $number2 == 0) {
			$content = '<div class="error">Все выплачено! Заявок нет.</div>';
		} else {
			
			if($_GET["option"] == "all" OR $_GET["option"] == "wait" OR empty($_GET["option"])) {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`system`,`info`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out` WHERE `status` = 0 ORDER BY `id` DESC");
			
				for($i = 1; $i <= $number; $i++) {
					$row = $query->fetch_assoc();
					unset($comment);
					
					$query2 = $db->query('SELECT `login`,`comment_am` FROM `users` WHERE `id` = "?i"', $row['id_user']);
					$row2 = $query2->fetch_assoc();
					
					$price = $row["price"]/100;
					
					if(!empty($row2["comment_am"])) {
						$comment = "<br>
									<span style='color: red'>".$row2["comment_am"]."</span>";
					}
				
					$body .= '
							<tr>
								<td>
									'.$row["id"].'
								</td>
								<td>
									#ID Кассира: '.$row["system"].'
								</td>
								<td>
									'.$row2["login"].'
									
									<div class="cashoutInf">
										<a href="/admin.php?do=listmaster&option=edit&id='.$row["id_user"].'">Профиль</a>
										<a href="/admin.php?do=logs&id='.$row["id_user"].'">Логи</a>
										<a href="/admin.php?do=listbet&filter=&status=0&user_name='.$row2["login"].'">Ставки</a>
									</div>
									'.$comment.'
								</td>
								<td>
									Сумма: '.$price.'
								</td>
								<td>
									'.$row["date"].'
								</td>
								<td>
									<div class="cashoutBlock">
										<div class="btn btn-primary right cashoutPay" data-id="'.$row["id"].'" data-status="1">Одобрить</div>
										
										<div class="btn btn-primary right cashoutPay" data-id="'.$row["id"].'" data-status="2">Отклонить</div>
									</div>
								</td>
							</tr>
					';
				}
			}
			if($_GET["option"] == "all" OR empty($_GET["option"])) {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`system`,`info`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out` WHERE `status` != 0 ORDER BY `id` DESC LIMIT 50");
			} elseif($_GET["option"] == "reject") {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`system`,`info`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out` WHERE `status` != 0 AND `status` != 1 ORDER BY `id` DESC LIMIT 50");
			} elseif($_GET["option"] == "close") {
				$query = $db->query("SELECT `id`,`id_user`,`price`,`system`,`info`,`number`,`date`,`status`,DATE_FORMAT(date,'%d/%m/%y %H:%i') as `date2` FROM `cash_out` WHERE `status` != 0 AND `status` != 2 ORDER BY `id` DESC LIMIT 50");
			}
			
			$row22 = $query->fetch_assoc_array();
			
			foreach($row22 as $key22 => $value22) {
				unset($comment);
				
				$query2 = $db->query('SELECT `login`,`comment_am` FROM `users` WHERE `id` = "?i"', $value22['id_user']);
				$row2 = $query2->fetch_assoc();
				
				$price = $value22["price"]/100;
				
				if(!empty($row2["comment_am"])) {
					$comment = "<br>
								<span style='color: red'>".$row2["comment_am"]."</span>";
				}
				
				if($value22["status"] == 2) {
					$status = "<img src='/master/template/image/icons/payment-fail.svg' style='width: 30px'> <br> Отклонена";
				} elseif($value22["status"] == 1) {
					$status = "<img src='/master/template/image/icons/payment-succ.svg' style='width: 30px'> <br> Выплачена";
				} elseif($value22["status"] == 4) {
					$status = "<img src='/master/template/image/icons/payment-succ.svg' style='width: 30px'> <br> Ожидает выплаты кассира";
				}
				
				$body2 .= '
						<tr>
							<td>
								'.$value22["id"].'
							</td>
							<td>
								#ID Кассира: '.$value22["system"].'
							</td>
							<td>
								'.$row2["login"].'
								
								<div class="cashoutInf">
									<a href="/admin.php?do=listmaster&option=edit&id='.$value22["id_user"].'">Профиль</a>
									<a href="/admin.php?do=logs&id='.$value22["id_user"].'">Логи</a>
									<a href="/admin.php?do=listbet&filter=&status=0&user_name='.$row2["login"].'">Ставки</a>
								</div>
								'.$comment.'
							</td>
							<td>
								Сумма: '.$price.'
							</td>
							<td>
								'.$value22["date"].'
							</td>
							<td>
								'.$status.'
							</td>
						</tr>
				';
			}
			
			$tpl = new template('admin/template/cashout-view.tpl');
			
			$tpl->set('{body}', $body);
			$tpl->set('{body2}', $body2);
			$content = $tpl->parse();
		}
	} else {
		if(empty($_GET["back"])) {
			$info_gen = GetKey(8);
			
			$db->query("UPDATE `cash_out` SET `status` = '?s', `info` = '?s' WHERE `id` = '?i'",4, $info_gen ,$_GET["id"]);
			header('Location: /admin.php?do=cashout');
		} else {
			$db->query("UPDATE `cash_out` SET `status` = '?s' WHERE `id` = '?i'", 2, $_GET["id"]);
			
			$query = $db->query("SELECT `id`,`id_user`,`price` FROM `cash_out` WHERE `id` = '?i'",$_GET["id"]);
			$row = $query->fetch_assoc();
			
			$query2 = $db->query("SELECT `balance` FROM `users` WHERE `id` = '?i'", $row["id_user"]);
			$row2 = $query2->fetch_assoc();
			
			$balance = $row2["balance"] + $row["price"];
			
			$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance, $row["id_user"]);
			header('Location: /admin.php?do=cashout');
		}
	}
?>
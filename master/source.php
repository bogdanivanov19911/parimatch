<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$query = $db->query("SELECT COUNT(id) FROM `sources` WHERE `status` = 0");
	$row = $query->fetch_assoc();
	$number = $row["COUNT(id)"];
	
	
	if(empty($_GET["id"])) {
		if($number == 0) {
			$content = '<div class="error">Нет источников на модерации!</div>';
		} else {
			$query = $db->query("SELECT * FROM `sources` WHERE `status` = 0 ORDER BY `id` DESC");
		
			for($i = 1; $i <= $number; $i++) {
				$row = $query->fetch_assoc();
				
				$query2 = $db->query('SELECT `login` FROM `users` WHERE `id` = "?i"', $row['user_id']);
				$row2 = $query2->fetch_assoc();
				
				if($row["type"] == 1) {
					$type = "Сайт";
				} elseif($row["type"] == 2) {
					$type = "E-mail";
				} elseif($row["type"] == 3) {
					$type = "Социальная сеть";
				} elseif($row["type"] == 4) {
					$type = "Рекламная сеть";
				}
				
				$body .= '
						<tr>
							<td>
								'.$type.': <a href="'.$row["url"].'">'.$row["url"].'</a> <br/>
								Комментарий: '.$row["comment"].' <br/>
								Пользователь: <a style="color: #656565" href="/master.php?do=users&option=edit&id='.$row["id_user"].'">'.$row2["login"].'</a> его ID: '.$row["id_user"].'
							</td>
							<td>
								<a href="/master.php?do=source&id='.$row["id"].'">
									<div class="btn btn-primary right">Одобрить</div>
								</a>
								
								<a href="/master.php?do=source&id='.$row["id"].'&back=true">
									<div class="btn btn-primary right">Отклонить</div>
								</a>
							</td>
						</tr>
				';
			}
			
			$tpl = new template('master/template/cashout-view.tpl');
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		}
	} else {
		if(empty($_GET["back"])) {
			$db->query("UPDATE `sources` SET `status` = '?s' WHERE `id` = '?i'",1,$_GET["id"]);
			header('Location: /master.php?do=source');
		} else {
			$db->query("UPDATE `sources` SET `status` = '?s' WHERE `id` = '?i'",2,$_GET["id"]);
			header('Location: /master.php?do=source');
		}
	}
?>
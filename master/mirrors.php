<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$query = $db->query("SELECT COUNT(id) FROM `info_tab`");
	$row = $query->fetch_assoc();
	$number = $row["COUNT(id)"];
	
	
	if(empty($_GET["id"]) AND empty($_GET["option"])) {
		if($number == 0) {
			$content = '<div class="error">Доменов нет!</div>';
		} else {
			$query = $db->query("SELECT * FROM `info_tab` ORDER BY `id` DESC");
		
			for($i = 1; $i <= $number; $i++) {
				$row = $query->fetch_assoc();
				
				unset($status);
				
				if($row["status"] == 1) $status = "<div style='display: inline-block; padding: 8px; background: #00ff5a; margin-left: 10px;'>Активный</div>";
				
				
				$body .= '
						<tr>
							<td>
								'.$row["value"].' 
								
								'.$status.'
							</td>
							<td>
								<a href="/master.php?do=mirrors&id='.$row["id"].'">
									<div class="btn btn-primary right">Активировать</div>
								</a>
							</td>
						</tr>
				';
			}
			
			$tpl = new template('master/template/mirrors-view.tpl');
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		}
	} elseif(empty($_GET["id"]) AND !empty($_GET["option"])) {
		if(!empty($_POST['name']) and !empty($_POST['url'])) {
			$query = $db->query('INSERT INTO `info_tab`(`name`,`value`) VALUES ("?s","?s")', $_POST["name"], $_POST["url"]);
			header('Location: /master.php?do=mirrors');
		}
		
		$tpl = new template('master/template/mirrors-add.tpl');
		$tpl->set('{body}', $body);
		$content = $tpl->parse();
	} else {
			$db->query("UPDATE `info_tab` SET `status` = '?i'", 0);
			$db->query("UPDATE `info_tab` SET `status` = '?i' WHERE `id` = '?i'", 1, $_GET["id"]);
			
			header('Location: /master.php?do=mirrors');
		
	}
?>
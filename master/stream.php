<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	if($_GET["option"] == "add") {
		if(!empty($_POST['title']) and !empty($_POST['src_h']) and isset($_POST['button'])) {
			$query = $db->query('INSERT INTO `frames`(`title`,`src`) VALUES ("?s","?s")', $_POST["title"],$_POST["src_h"]);
			header('Location: /master.php?do=stream');
		} else {
			$content = '<div class="error">Вы забыли заполнить какое-то поле!</div>';
		}
		
		if(empty($_POST)) {
			$tpl = new template('master/template/stream-add.tpl');
			$tpl->set('{date_now}', $date_now);
			$content = $tpl->parse();
		}
	} elseif($_GET["option"] == "delete" and !empty($_GET["id"])) {
		$content = '
		<div class="error">
			Подтвердите удаление!
			<div>
				<form enctype="multipart/form-data" action="" method="post">
					<button type="sumbit" class="btn btn-primary right" name="button">Продолжить</button>
				</form>
			</div>
		</div>';
		
		if(isset($_POST['button'])) {
			$db->query("DELETE FROM `frames` WHERE `id` = '?i'", $_GET["id"]);
			header('Location: /master.php?do=stream');
		}
	} else {
		$query = $db->query('SELECT * FROM `frames` ORDER BY `id` DESC');
		$games_array = $db->getAffectedRows();
		for($i=0;$i<$games_array;$i++) {
			$row = $query->fetch_assoc();
			$body .= '
					<tr>
						<td>
							'.$row["title"].'
						</td>
						<td>
							<a href="/master.php?do=stream&option=delete&id='.$row["id"].'">
								<div class="btn btn-primary right">Удалить</div>
							</a>
						</td>
					</tr>
			';
		}
		
		if(empty($_POST)) {
			$tpl = new template('master/template/stream-view.tpl');
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		}
	}
?>
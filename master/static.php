<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	if($_GET["option"] == "add") {
		if(!empty($_POST['title']) and !empty($_POST['text']) and isset($_POST['button'])) {
			
			$_POST["text"] = str_replace("\n","<br>",$_POST["text"]);
			
			$query = $db->query('INSERT INTO `static_page`(`title`,`text`) VALUES ("?s","?s")', $_POST["title"],$_POST["text"]);
			header('Location: /master.php?do=static');
		} else {
			$content = '<div class="error">Вы забыли заполнить какое-то поле!</div>';
		}
		
		if(empty($_POST)) {
			$tpl = new template('master/template/static-add.tpl');
			$tpl->set('{date_now}', $date_now);
			$content = $tpl->parse();
		}
	} elseif($_GET["option"] == "edit" and !empty($_GET["id"])) {
		$query = $db->query('SELECT * FROM `static_page` WHERE `id` = "?i"', $_GET['id']);
		$row = $query->fetch_assoc();
		$row_check = $db->getAffectedRows();
		
		if($row_check == 1) {
			if(!empty($_POST['title']) and !empty($_POST['text']) and isset($_POST['button'])) {
				$query = $db->query('UPDATE `static_page` SET `title` = "?s", `text` = "?s" WHERE `id` = "?i"', $_POST["title"],$_POST["text"],$_GET["id"]);
				header('Location: /master.php?do=static');
			} else {
				$content = '<div class="error">Вы забыли заполнить какое-то поле!</div>';
			}
			
			if(empty($_POST)) {
				$tpl = new template('master/template/static-edit.tpl');
				$tpl->set('{title}', $row["title"]);
				$tpl->set('{text}', $row["text"]);
				$content = $tpl->parse();
			}
		} else {
			$content = '<div class="error">Страница не найдена!</div>';
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
			$db->query("DELETE FROM `static_page` WHERE `id` = '?i'", $_GET["id"]);
			header('Location: /master.php?do=static');
		}
	} else {
		$query = $db->query('SELECT * FROM `static_page` ORDER BY `id` DESC');
		$games_array = $db->getAffectedRows();
		for($i=0;$i<$games_array;$i++) {
			$row = $query->fetch_assoc();
			$body .= '
					<tr>
						<td>
							'.$row["title"].'
						</td>
						<td>
							<a href="/master.php?do=static&option=delete&id='.$row["id"].'">
								<div class="btn btn-primary right">Удалить</div>
							</a>
							<a href="/master.php?do=static&option=edit&id='.$row["id"].'">
								<div class="btn btn-primary right">Изменить</div>
							</a>
						</td>
					</tr>
			';
		}
		
		if(empty($_POST)) {
			$tpl = new template('master/template/static-view.tpl');
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		}
	}
?>
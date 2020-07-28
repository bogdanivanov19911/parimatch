<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$query = $db->query('SELECT * FROM `static_page` WHERE `id` = "?i"', $_GET['id']);
	$row = $query->fetch_assoc();
	
	if($db->getAffectedRows() == 1) {
		$tpl = new template('template/static.tpl');
		$tpl->set('{title}', $row["title"]);
		$tpl->set('{text}', $row["text"]);
		$body = $tpl->parse();
	} else {
		$body = '<div class="no-bets">Страница не найдена!</div>';
	}
?>
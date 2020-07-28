<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	
	$date_events = date("Y-m-d H:i:s");
	
	if(empty($_POST)) {
		$queryEvent = $db->query('SELECT `id`,`tournament_id` FROM `events` WHERE `is_live` = 0 AND `time_start` >= "'.$date_events.'"');
		$rowEvent = $queryEvent->fetch_assoc_array();
		
		$tournArray = array();
		
		foreach ($rowEvent as $key3 => $value3) {
			$tournArray[] = $value3["tournament_id"];
		}
		
		$tournStr = implode(",",$tournArray);
		
		if(!empty($_GET["search"])) {
			$query55 = $db->query('SELECT * FROM `tournaments` WHERE `id` IN ( '.$tournStr.' ) AND `name` LIKE "%?s%"', $_GET["search"]);
		} else {
			$query55 = $db->query('SELECT * FROM `tournaments` WHERE `id` IN ( '.$tournStr.' ) LIMIT 200');
		}
		
		$row55 = $query55->fetch_assoc_array();
		
		foreach($row55 as $key => $value) {
			
			if($value["block"] == 0) {
				$div = '		<option value="">None</option>
								<option value="0" selected>Off</option>
								<option value="1">On</option>';
			} elseif($value["block"] == 1) {
				$div = '		<option value="">None</option>
								<option value="0">Off</option>
								<option value="1" selected>On</option>';
			} else {
				$div = '		<option value="" selected>None</option>
								<option value="0">Off</option>
								<option value="1" selected>On</option>';
			}
			
			$body .= '
					<tr>
						<td>
							'.$value["name"].'
						</td>
						<td>
							<select name="'.$value["id"].'">
								'.$div.'
							</select>
						</td>
					</tr>
			';
		}
		
		$tpl = new template('master/template/hot-tournament.tpl');
		$tpl->set('{body}', $body);
		$content = $tpl->parse();
	} else {
		
		foreach($_POST as $key => $value) {
			if($key != "button") {
				if($value == 0 OR $value == 1) {
					$db->query('UPDATE `tournaments` SET `block` = "?i" WHERE `id` = "?i"', $value,$key);
				}
			}
		}
		header('Location: /master.php?do=hot-tournament');
	}
?>
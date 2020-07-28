<?php

	if(isset($_COOKIE['hash']) and isset($_COOKIE['id'])) {
		$query = $db->query('SELECT * FROM `users` WHERE `hash` = "?s" AND `id` = "?i" LIMIT 1', $_COOKIE['hash'], $_COOKIE['id']);
		$user = $query->fetch_assoc();
		
		if($db->getAffectedRows() == 1) {
			if($user["status"] == 1) {
				$logged = FALSE;
			} else {
				$logged = TRUE;
				
				$query = $db->query("SELECT `id`,`id_bets`,`rate`,`price`,`user_id`,`type`,`factor`,`bet_result`,`bet_status` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` = '0'", $user["id"]);
				
				while($row = $query->fetch_assoc()) {
					$unresolved[] = $row["price"];
				}
				
				if(!empty($unresolved)) {
					$user["unresolved"] = "";
					for($i=0,$c = count($unresolved);$i < $c;++$i) {
						$user["unresolved"] += $unresolved[$i];
					}
				} else {
					$user["unresolved"] = "0.00";
				}
			}
		} else {
			$logged = FALSE;
		}
	} else {
		$logged = FALSE;
	}
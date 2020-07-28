<?php
	if(!defined('bk')) die('Hacking Attempt!');
    date_default_timezone_set("UTC");

    $d1 = strtotime(date("Y-m-d H:i:s")) - 150;
	
	$curr_timeLive = date("Y-m-d H:i:s",$d1);
	
	$date_events = date("Y-m-d H:i:s");
	$_COOKIE['hour'] = "+2";
	$new_date = strtotime("+3 hours");
	
	$date_events = date("Y-m-d H:i:s",$new_date);
	
	$games = "";
	
	function limitBet($factor,$maxbet) {
		$percent = 1/$factor; // Процент победы "0.55"
		$limit_now = $maxbet * $percent;
		
		return round($limit_now);
	}
	
	function compare($v1, $v2) {
		if($v1[1]["lv"] == $v2[1]["lv"]) return 0;
		return ($v1[1]["lv"] < $v2[1]["lv"])? -1: 1;
	}
	
	$query = $db->query('SELECT *,DATE_FORMAT(time_start + INTERVAL "?s" HOUR,"%d/%m|%Y|%H:%i|%Y-%m-%d %H:%i:%s") as `date_start` FROM `events` WHERE `result` IS NULL AND `is_live` = 1 AND `curr_time` > "?s" ORDER BY `parse_id` DESC', $_COOKIE['hour'], $curr_timeLive);
	$row = $query->fetch_assoc_array();
	
	if($row[0] == "") {
		$body = '<div class="no-bets">Обработка линии, пожалуйста, подождите!</div>';
	} else {
		$events_array = array();
		foreach($row as $data) {
			$events_array[$data["tournament_id"]][] = $data;
			$game_array[$data["game_id"]] = "";
		}
		
		foreach($row as $data) {
			$game_array[$data["game_id"]] .= $data["tournament_id"].",";
		}
		
		$game_array = array_unique($game_array);
		$keys = array_keys($game_array);
		
		$query = $db->query('SELECT `id`,`name`,`name_translate`,`short_name`,`icon`,`sorting` FROM `games` WHERE `id` IN (?as) ORDER BY `sorting`',$keys);
		while ($rows = $query->fetch_assoc()) {
			unset($tournament);
			
			$game = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-game.tpl');
			$tournament = "";	
			
				$tournaments = explode(",", chop($game_array[$rows["id"]],","));
				$query2 = $db->query('SELECT `id`,`name`,`name_translate`,`game`,`sorting` FROM `tournaments` WHERE `id` IN (?as) ORDER BY `name_translate`',$tournaments);
			
				while($row2 = $query2->fetch_assoc()) {
					$tourn = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-tournament.tpl');
					$events = "";
	
						foreach($events_array[$row2["id"]] as $row3) {
							$event = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-event-live.tpl');
							$date_start = explode("|",$row3["date_start"]);
							$c = 0;
							$row3["limits"] = 1000000;
							
							$evt = json_decode($row3["bets"], true);

							
							usort($evt["T"], "compare");
							usort($evt["F"], "compare");
							
							require($_SERVER["DOCUMENT_ROOT"]."/engine/ajax/line.php");
							
								if(!empty($evt["1X2"][1]["kf"])) {
									$event->set('{bet-status-class}', "bet-active");
									$factor1 = $evt["1X2"][1]["kf"];
									$event->set('{factor_1}', $factor1);
									$event->set('{limit_1}', limitBet($factor1,$row3["limits"]));
								} else {
									$event->set('{bet-status-class}', "bet-no-active");
									$event->set('{factor_1}', "-/-");
								}
								
								if(!empty($evt["1X2"][2]["kf"])) {
									$event->set('{bet-status-class}', "bet-active");
									$factor2 = $evt["1X2"][2]["kf"];
									$event->set('{factor_2}', $factor2);
									$event->set('{limit_2}', limitBet($factor2,$row3["limits"]));
								} else {
									$event->set('{bet-status-class}', "bet-no-active");
									$event->set('{factor_2}', "-/-");
								}
								
								if(!empty($evt["1X2"][3]["kf"])) {
									$event->set('{bet-status-class-x}', "bet-active");
									$factor3 = $evt["1X2"][3]["kf"];
									$event->set('{factor_3}', $factor3);
									$event->set('{limit_3}', limitBet($factor3,$row3["limits"]));
								} else {
									$event->set('{bet-status-class-x}', "bet-no-active");
									$event->set('{factor_3}', "-/-");
								}
								
								$event->set('{more-result}', '
								<ul class="more-bet">
									'.$more_result.'
								</ul>');
							
							if(!empty($row3["score"])) {
								$event->set('{score}', $row3["score"]);
							} else {
								$event->set('{score}', "");
							}
							
							if(!empty($row3["score_all"])) {
								$event->set('{score_all}', "(".$row3["score_all"].")");
							} else {
								$event->set('{score_all}', "");
							}
							
							if(!empty($row3["min"])) {
								$event->set('{time_start}', "<span class='minutes'>".$row3["min"]." '</span>");
							} else {
								$event->set('{time_start}', "");
							}
							
							$event->set('{name_1}', $row3["name_1"]);
							$event->set('{name_2}', $row3["name_2"]);
							$event->set('{more-result-count}', $c);
							$event->set('{bet_id}', $row3["id"]);
							$event->set('{id}', $row3["id"]);
							$event->set('{game_name}', $rows["name"]);
							$event->set('{tournament_name}', $row2["name"]);
							$event->set('{date_start}', $date_start[0]);
							$event->set('{unix_start}', strtotime($date_start[3]));
							$events .= $event->parse()."\n\n";
							unset($more_result,$evt);
						}
						
						
						
					$tourn->set('{name}', $row2["name"]);
					$tourn->set('{idT}', $row2["id"]);
					$tourn->set('{event}', "<table class='bet-table'>".$events."</table>");
					$tournament .= $tourn->parse()."\n\n";
					
					unset($events);
				}
			
			
			if(!empty($tournament)) {				
				$game_line .= '	<live-navigation-sport class="live-navigation-item">
									<i class="icon sporticon" data-game="'.$rows["id"].'"></i>
									<span class="live-navigation-item__name">'.$rows["name"].'</span>
									<span class="sportborder-F">
										<span class="live-navigation-item__arrow"></span>
									</span>
								</live-navigation-sport>';
				$game->set('{name}', $rows["name"]);
				$game->set('{short-name}', $rows["short_name"]);
				$game->set('{idG}', $rows["id"]);
				$game->set('{tournament}', $tournament);
				$game->set('{icon}', $rows["icon"]);
				$games .= $game->parse()."\n\n";
			}
		}
		
		$game_line_block = '
					<div class="sport-label sport-label_live">Live Ставки</div>
					<div class="select-game-block">
						<div class="swiper-wrapper live-navigation">
							'.$game_line.'
						</div>
					</div>
					<sport-filter class="sport-filter sportcolor-bg-F"><div class="sport-filter__event"> События</div><line-filter class="filter sport-filter__event sport-filter-dropdown"> <custom-select value="2" class="custom-select"><div class="select-cool custom-select__title"> Исход </div></custom-select></line-filter></sport-filter>
		';
		
		$body = $game_line_block.$games;
	}
?>
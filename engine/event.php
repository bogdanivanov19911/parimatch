<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$date = date_create(date("Y-m-d H:i:s"));
	
	$_COOKIE['hour'] = "+0";
	

	$date->modify("+".$_COOKIE['hour']." hour");
	$date_now = date_format($date,"Y-m-d H:i:s");
	$time_now = date_format($date,"H:i");
	
	$db->setTypeMode(Database_Mysql::MODE_TRANSFORM);
	
	
	$d1 = strtotime(date("Y-m-d H:i:s")) - 30;
	
	$curr_timeLive = date("Y-m-d H:i:s",$d1);
	
	$date_events = date("Y-m-d H:i:s");
	
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
	
	$query = $db->query('SELECT *,DATE_FORMAT(time_start + INTERVAL "?s" HOUR ,"%d/%m|%Y|%H:%i|%Y-%m-%d %H:%i:%s") as `date_start` FROM `events` WHERE `result` IS NULL AND `id` = "?i"', $_COOKIE['hour'], $_GET['id']);
	$row = $query->fetch_assoc();
	
	if($db->getAffectedRows() == 0) {
		$body = '<div class="closed-event-body">
					<h1 class="closed-event-body__title">Событие завершено</h1>
					<p class="closed-event-body__description">Событие, которое вы просматривали, завершено или больше не доступно.</p>
					<a class="closed-event-body__link" href="/">Вернуться на главную</a>
					<div class="closed-event-btn-wrap">
						<a style="text-decoration: none; line-height: 48px;" href="/?do=live" class="closed-event__btn btn-box btn-red" > СЕЙЧАС В LIVE </a>
					</div>
				</div>';
	} else {
		
		$query = $db->query('SELECT `id`,`name`,`name_translate`,`short_name`,`icon`,`sorting` FROM `games` WHERE `id` = "?i"',$row["game_id"]);
		$row3 = $query->fetch_assoc();

		$game = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-game.tpl');
		
		$query2 = $db->query('SELECT `id`,`name`,`name_translate`,`game`,`sorting` FROM `tournaments` WHERE `id` = "?i"',$row["tournament_id"]);
		$row2 = $query2->fetch_assoc();

		$tourn = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-tournament.tpl');
		
		
		
							$event = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-event-in.tpl');
							$date_start = explode("|",$row["date_start"]);
							$c = 0;
							$row["limits"] = 1000000;
							
							$evt = json_decode($row["bets"], true);
							
							usort($evt["T"], "compare");
							usort($evt["F"], "compare");

							require_once($_SERVER["DOCUMENT_ROOT"]."/engine/ajax/line.php");
							
							if(!empty($row["score"])) {
								$event->set('{score}', $row["score"]);
								$score_all_part2_piece = explode(":", $row["score"]);
								$team1_scoreAll = "<td class='scoreboard__total-score'><b>".$score_all_part2_piece[0]."</b></td>";
								$team2_scoreAll = "<td class='scoreboard__total-score'><b>".$score_all_part2_piece[1]."</b></td>";
								$team_all_numbSC = "<td>Общ.</td>";
							} else {
								$event->set('{score}', "");
							}
							
							if($c == 0) {
								$score_all = "<span style='color:#ff0000'>Прием ставок приостановлен!</span>";
								$event->set('{more-result}', '<div class="closed-event-body">
									<h1 class="closed-event-body__title" style="color:#ff0000">Событие приостановлено</h1>
									<p class="closed-event-body__description">Событие, которое вы просматривали, временно приостановлено.</p>
									<div class="closed-event-btn-wrap">
										<a style="text-decoration: none; line-height: 48px;" href="/?do=live" class="closed-event__btn btn-box btn-red" > СЕЙЧАС В LIVE </a>
									</div>
								</div>');
							} else {
								if(!empty($row["score_all"])) {
									$event->set('{score_all}', "(".$row["score_all"].")");
									
									
									$score_all_part = explode(",",$row["score_all"]);
									
									$score_time_n = 1;
									
									foreach($score_all_part as $key => $value) {
										$score_all_part_piece = explode(":", $value);
										
										
										$team1_score .= "<td class='scoreboard__total-score'><b>".$score_all_part_piece[0]."</b></td>";
										$team2_score .= "<td class='scoreboard__total-score'><b>".$score_all_part_piece[1]."</b></td>";
										$team_all_numb .= "<td>".$score_time_n."</td>";
										$score_time_n++;
									}
									
									
								} else {
									$score_all = "";
								}
								$team_all_numb = $team_all_numb.$team_all_numbSC;
								
								
								$event->set('{more-result}', $more_result);
							}
							
							if(!empty($row["min"])) {
								$min = $row["min"].'`';
							} else {
								$min = $date_start[2];
							}
							
							$event->set('{name_1}', $row["name_1"]);
							$event->set('{name_2}', $row["name_2"]);
							$event->set('{more-result-count}', $c);
							$event->set('{bet_id}', $row["id"]);
							$event->set('{id}', $row["id"]);
							$event->set('{game_name}', $rows["name"]);
							$event->set('{tournament_name}', $row2["name"]);
							$event->set('{date_start}', $date_start[0]);
							$event->set('{time_start}', $date_start[2]);
							$event->set('{unix_start}', strtotime($date_start[3]));
							$events .= $event->parse()."\n\n";
							unset($more_result,$evt);
						
					$tourn->set('{name}', $row2["name"]);
					$tourn->set('{idT}', $row2["id"]);
					$tourn->set('{event}', "<table class='bet-table'>".$events."</table>");
					$tournament .= $tourn->parse()."\n\n";
		
				$game->set('{name}', $row3["name"]);
				$game->set('{short-name}', $row3["short_name"]);
				$game->set('{idG}', $row3["id"]);
				$game->set('{tournament}', $tournament);
				$game->set('{icon}', $row3["icon"]);
				$games = $game->parse()."\n\n";
		
		
		if($row["is_live"] == 0) {
			$href = '/?do=line&league='.$row2["id"].'';
		} else {
			$href = '/';
		}
		
		$content_header = '
					<div class="topbar topbar_event topbar-event__grid" data-gamebg="'.$row3["id"].'">
						<div class="topbar__col topbar__col_left">
							<a class="btn topbar__left" href="'.$href.'">
								<i class="icon icon-ai-angle-left icon_white"></i>
							</a>
						</div>
						<div class="topbar__col topbar__col_center">
							<span class="topbar__title">'.$row2["name"].'</span>
						</div>
						<div class="topbar__col topbar__col_right"></div>
					</div>
		
						<score-board class="scoreboard">
							<table class="scoreboard-table">
								<thead>
									<tr>
										<td class="scoreboard__time">
											<b class="scoreboard__total-score"> '.$min.' </b>
										</td>
										<td class="scoreboard__date">
											<span>'.$date_start[0].' / '.$date_start[2].'</span>
										</td>
										'.$team_all_numb.'
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="2" class="scoreboard__name ">
											<span>'.$row["name_1"].'</span>
										</td>
										'.$team1_score.'
										'.$team1_scoreAll.'
									</tr>
									<tr>
										<td colspan="2" class="scoreboard__name ">
											<span>'.$row["name_2"].'</span>
										</td>
										'.$team2_score.'
										'.$team2_scoreAll.'
									</tr>
								</tbody>
							</table>
						</score-board>';
		$body = $content_header.$events;
		

		
		
	}
?>
<?php
	header("Content-Type: text/html; charset=utf-8");
	define('bk', true);
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/function.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/templater.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/mysqli.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
	
	$ip = ip2long($_SERVER['REMOTE_ADDR']);
	
	date_default_timezone_set("UTC");
	
	$date = date_create(date("Y-m-d H:i:s"));
	
	$_COOKIE['hour'] = "0";

	$date->modify("+3 hour");
	$date_now = date_format($date,"Y-m-d H:i:s");
	$time_now = date_format($date,"H:i");
	
	$db->setTypeMode(Database_Mysql::MODE_TRANSFORM);
	
	$nameTeam = $_POST["nameteam"];
	
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
	
	$query = $db->query('SELECT *,DATE_FORMAT(time_start + INTERVAL "?s" HOUR ,"%d/%m|%Y|%H:%i|%Y-%m-%d %H:%i:%s") as `date_start` FROM `events` WHERE `result` IS NULL AND `is_live` = 1 AND `curr_time` > "?s" AND (`name_1` LIKE "%?s%" OR `name_2` LIKE "%?s%") ORDER BY `parse_id` DESC LIMIT 20', $_COOKIE['hour'], $curr_timeLive, $nameTeam, $nameTeam);
	$row = $query->fetch_assoc_array();
	
	if($row[0] == "") {
		
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
							
							if(!empty($evt["WXM"][1]["kf"])) {
								$title_exd = "Двойной исход: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
									$bet_status_class = "bet-active";
									$ev_finder = "WXM";
									
									$factor1 = $evt["WXM"][1]["kf"];
									$factor2 = $evt["WXM"][2]["kf"];
									$factor3 = $evt["WXM"][3]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									$limit3 = limitBet($factor3,$row3["limits"]);
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' 1X" data-result="1"><span><font style="direction: ltr; display: inline-block;">1X</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' 12" data-result="2"><span><font style="direction: ltr; display: inline-block;">12</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
													<li>
														<span class="factor factor3 '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' X2" data-result="3"><span><font style="direction: ltr; display: inline-block;">X2</font></span> <a class="price" data-limit="'.$limit3.'">'.$factor3.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$factor3,$limit1,$limit2,$limit3);
									$more_result .= $more_bet."\n\n";
									$c++;
								
								$more_result .= "</li>\n\n";
							}
							
							
							if(!empty($evt["F"][0])) {
								$title_exd = "Фора: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["F"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="F" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$row3["name_1"].' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$row3["name_1"].' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="F" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$row3["name_2"].' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$row3["name_2"].' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							
							if(!empty($evt["T"][0])) {
								$title_exd = "Тотал: ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["T"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="T" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="T" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["SCR0"])) {
								$title_exd = "Точный счет: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["SCR0"] as $kkk => $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									
										$more_bet_in .= 			'<li>
														<span class="factor '.$bet_status_class.'" data-finder="SCR0" data-id="'.$row3["id"].'" data-name="'.$title_exd.' ('.$namesArray["SCR0"]["name_ex"][$kkk].')" data-result="'.$kkk.'"><span><font style="direction: ltr; display: inline-block;">'.$namesArray["SCR0"]["name_ex"][$kkk].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>';
									
											
									unset($factor1,$limit1);
									$c++;
								}
									$more_bet = '
												<ul>
													'.$more_bet_in.'
												</ul>';
								
									$more_result .= $more_bet."\n\n";
								$more_result .= "</li>\n\n";
							}
							
							
							if(!empty($evt["OZIN12TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Голы в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN12TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZTEAM"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZTEAM";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["GOALS1"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Команда 1 забьет гол: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "GOALS1";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["GOALS2"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "Команда 2 забьет гол: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "GOALS2";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["1TIMEHAVEGOALS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "1 тайм будут голы: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "1TIMEHAVEGOALS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["2TIMEHAVEGOALS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "2 тайм будут голы: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "2TIMEHAVEGOALS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TIMERESULTS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "Результативность таймов: ";
								$name_exd["1"] = "1>2";
								$name_exd["2"] = "1<2";
								$name_exd["3"] = "1=2";
								$ev_finder = "TIMERESULTS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TOTALYNCHET"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Тотал чет/нечет ";
								$name_exd["1"] = "Чет";
								$name_exd["2"] = "Нечет";
								$ev_finder = "TOTALYNCHET";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["INDTOTAL1"])) {
								$title_exd = "Индивидуальный тотал ".$row3["name_1"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL1"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["INDTOTAL2"])) {
								$title_exd = "Индивидуальный тотал ".$row3["name_2"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL2"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["OZIN1TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют в первом тайме: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN1TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZIN2TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют во втором тайме: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN2TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TEAM1GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_1"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM1GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TEAM2GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_2"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM2GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZANDTOTALUNDER"])) {
								$title_exd = "Обе забьют и тотал меньше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALUNDER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["OZANDTOTALOVER"])) {
								$title_exd = "Обе забьют и тотал больше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALOVER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="OZANDTOTALOVER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="OZANDTOTALOVER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["W1ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_1"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W1ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["W2ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_2"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W2ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							
							
							
							
							
							
							
							
							
							
							
							
							
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
								$event->set('{min}', "<span class='minutes'>".$row3["min"]." '</span>");
							} else {
								$event->set('{min}', "");
							}
							
							$event->set('{name_1}', $row3["name_1"]);
							$event->set('{name_2}', $row3["name_2"]);
							$event->set('{more-result-count}', $c);
							$event->set('{bet_id}', $row3["id"]);
							$event->set('{id}', $row3["id"]);
							$event->set('{game_name}', $rows["name"]);
							$event->set('{tournament_name}', $row2["name"]);
							$event->set('{date_start}', $date_start[0]);
							$event->set('{time_start}', $date_start[2]);
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
				$game->set('{name}', $rows["name"]);
				$game->set('{short-name}', $rows["short_name"]);
				$game->set('{idG}', $rows["id"]);
				$game->set('{tournament}', $tournament);
				$game->set('{icon}', $rows["icon"]);
				$games .= $game->parse()."\n\n";
			}
		}
		
		$body .= $games;
	}
	
	
	
	$games = "";
	
	$query = $db->query('SELECT *,DATE_FORMAT(time_start + INTERVAL "?s" HOUR ,"%d/%m|%Y|%H:%i|%Y-%m-%d %H:%i:%s") as `date_start` FROM `events` WHERE `result` IS NULL AND `is_live` = 0 AND `time_start` >= "'.$date_now.'" AND (`name_1` LIKE "%?s%" OR `name_2` LIKE "%?s%") ORDER BY `time_start`', 0 , $nameTeam,$nameTeam);
	$row = $query->fetch_assoc_array();
	
	if($row[0] == "") {
		$body .= '<div class="no-bets">Матчи не найдены!</div>';
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
							$event = new template($_SERVER["DOCUMENT_ROOT"].'/template/line-event.tpl');
							$date_start = explode("|",$row3["date_start"]);
							$c = 0;
							$row3["limits"] = 1000000;
							
							$evt = json_decode($row3["bets"], true);
							
							usort($evt["T"], "compare");
							usort($evt["F"], "compare");
							
							if(!empty($evt["WXM"][1]["kf"])) {
								$title_exd = "Двойной исход: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
									$bet_status_class = "bet-active";
									$ev_finder = "WXM";
									
									$factor1 = $evt["WXM"][1]["kf"];
									$factor2 = $evt["WXM"][2]["kf"];
									$factor3 = $evt["WXM"][3]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									$limit3 = limitBet($factor3,$row3["limits"]);
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' 1X" data-result="1"><span><font style="direction: ltr; display: inline-block;">1X</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' 12" data-result="2"><span><font style="direction: ltr; display: inline-block;">12</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
													<li>
														<span class="factor factor3 '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' X2" data-result="3"><span><font style="direction: ltr; display: inline-block;">X2</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$factor3,$limit1,$limit2,$limit3);
									$more_result .= $more_bet."\n\n";
									$c++;
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["F"][0])) {
								$title_exd = "Фора: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["F"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="F" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$row3["name_1"].' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$row3["name_1"].' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="F" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$row3["name_2"].' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$row3["name_2"].' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							
							if(!empty($evt["T"][0])) {
								$title_exd = "Тотал: ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["T"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="T" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="T" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}		
							
							if(!empty($evt["SCR0"])) {
								$title_exd = "Точный счет: ";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["SCR0"] as $kkk => $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									
										$more_bet_in .= 			'<li>
														<span class="factor '.$bet_status_class.'" data-finder="SCR0" data-id="'.$row3["id"].'" data-name="'.$title_exd.' ('.$namesArray["SCR0"]["name_ex"][$kkk].')" data-result="'.$kkk.'"><span><font style="direction: ltr; display: inline-block;">'.$namesArray["SCR0"]["name_ex"][$kkk].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>';
									
											
									unset($factor1,$limit1);
									$c++;
								}
									$more_bet = '
												<ul>
													'.$more_bet_in.'
												</ul>';
								
									$more_result .= $more_bet."\n\n";
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["OZIN12TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Голы в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN12TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZTEAM"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZTEAM";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["GOALS1"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Команда 1 забьет гол: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "GOALS1";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["GOALS2"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "Команда 2 забьет гол: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "GOALS2";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["1TIMEHAVEGOALS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "1 тайм будут голы: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "1TIMEHAVEGOALS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["2TIMEHAVEGOALS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "2 тайм будут голы: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "2TIMEHAVEGOALS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TIMERESULTS"][2]["kf"])) {
								unset($more_bet);
								$title_exd = "Результативность таймов: ";
								$name_exd["1"] = "1>2";
								$name_exd["2"] = "1<2";
								$name_exd["3"] = "1=2";
								$ev_finder = "TIMERESULTS";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TOTALYNCHET"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Тотал чет/нечет ";
								$name_exd["1"] = "Чет";
								$name_exd["2"] = "Нечет";
								$ev_finder = "TOTALYNCHET";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["INDTOTAL1"])) {
								$title_exd = "Индивидуальный тотал ".$row3["name_1"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL1"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["INDTOTAL2"])) {
								$title_exd = "Индивидуальный тотал ".$row3["name_2"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL2"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["OZIN1TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют в первом тайме: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN1TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZIN2TYME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = "Обе забьют во втором тайме: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "OZIN2TYME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TEAM1GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_1"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM1GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["TEAM2GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_2"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM2GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["OZANDTOTALUNDER"])) {
								$title_exd = "Обе забьют и тотал меньше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALUNDER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["OZANDTOTALOVER"])) {
								$title_exd = "Обе забьют и тотал больше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALOVER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row3["limits"]);
									$limit2 = limitBet($factor2,$row3["limits"]);
									
									
									$more_bet = '
												<ul>
													<li>
														<span class="factor '.$bet_status_class.'" data-finder="OZANDTOTALOVER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><span>'.$name_exd1.' <font style="direction: ltr; display: inline-block;">'.$rowBet[1]["lv"].'</font></span> <a class="price" data-limit="'.$limit1.'">'.$factor1.'</a></span>
													</li>
													<li>
														<span class="factor factor2 '.$bet_status_class.'" data-finder="OZANDTOTALOVER" data-id="'.$row3["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><span>'.$name_exd2.' <font style="direction: ltr; display: inline-block;">'.$rowBet[2]["lv"].'</font></span> <a class="price" data-limit="'.$limit2.'">'.$factor2.'</a></span>
													</li>
												</ul>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c++;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["W1ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_1"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W1ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							if(!empty($evt["W2ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row3["name_2"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W2ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row3["limits"]);
									$more_bet .= '
										<li>
											<span class="factor bet-active" data-finder="'.$ev_finder.'" data-id="'.$row3["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><span>'.$name_exd[$res].'</span> <a class="price" data-limit="'.$limit.'">'.$factor.'</a></span>
										</li>
									';
								
									$res++;
								}
								
								$more_result .= '
											<li class="exodus" style="font-size: 15px;">
												<div class="more-bet-title">'.$title_exd.'</div>
												<ul>'.$more_bet.'</ul>
											</li>';
											
								$c++;
							}
							
							
							
							
							
							
							
							
							
							
							
							
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
								$event->set('{min}', "<span class='minutes'>".$row3["min"]." '</span>");
							} else {
								$event->set('{min}', "");
							}
							
							$event->set('{name_1}', $row3["name_1"]);
							$event->set('{name_2}', $row3["name_2"]);
							$event->set('{more-result-count}', $c);
							$event->set('{bet_id}', $row3["id"]);
							$event->set('{id}', $row3["id"]);
							$event->set('{game_name}', $rows["name"]);
							$event->set('{tournament_name}', $row2["name"]);
							$event->set('{date_start}', $date_start[0]);
							$event->set('{time_start}', $date_start[2]);
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
				$game->set('{name}', $rows["name"]);
				$game->set('{short-name}', $rows["short_name"]);
				$game->set('{idG}', $rows["id"]);
				$game->set('{tournament}', $tournament);
				$game->set('{icon}', $rows["icon"]);
				$games .= $game->parse()."\n\n";
			}
		}
		
		$body .= $games;
	}
	
	$game_line_block = '<sport-filter class="sport-filter sportcolor-bg-F"><div class="sport-filter__event"> События</div><line-filter class="filter sport-filter__event sport-filter-dropdown"> <custom-select value="2" class="custom-select"><div class="select-cool custom-select__title"> Исход </div></custom-select></line-filter></sport-filter>';
		
	$body = $game_line_block.$games;
	
	
	echo $body;
?>
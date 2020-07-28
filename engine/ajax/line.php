<?php 
	if(!defined('bk')) die('Hacking Attempt!');
	
	$namesArray = array(
						"SCR0" => array(
									"title" => "Точный счет: ",
									"teams" => FALSE,
									"name_ex" => array(
										"1" => "1:0",
										"2" => "2:0",
										"3" => "3:0",
										"4" => "4:0",
										"5" => "2:1",
										"6" => "3:1",
										"7" => "3:2",
										"8" => "4:1",
										"9" => "4:2",
										"10" => "4:3",
										"11" => "1:1",
										"12" => "2:2",
										"13" => "3:3",
										"14" => "4:4",
										"15" => "0:1",
										"16" => "0:2",
										"17" => "0:3",
										"18" => "0:4",
										"19" => "1:2",
										"20" => "1:3",
										"21" => "2:3",
										"22" => "1:4",
										"23" => "2:4",
										"24" => "3:4",
									),
									"for" => FALSE,
									"lv" => FALSE,
						),
	);
	
	
							unset($c);
							$c = 0;

							if(!empty($evt["1X2"])) {
								$bet_status_class = "bet-active";
								$title_exd = "Победитель матча: ";
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
									$ev_finder = "1X2";
									$factor1 = $evt["1X2"][1]["kf"];
									$factor2 = $evt["1X2"][2]["kf"];
									$factor3 = $evt["1X2"][3]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									$limit3 = limitBet($factor3,$row["limits"]);
									
									$more_bet = '
									<div class="outcome-group-wrapper">
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.'  '.$row["name_1"].'" data-result="1"><div class="title">'.$row["name_1"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$row["name_2"].'" data-result="2"><div class="title">'.$row["name_2"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' Ничья" data-result="3"><div class="title">Ничья</div><div class="kef" data-limit="'.$limit3.'">'.$factor3.'</div></div>
									</div>
									';
									
									unset($factor1,$factor2,$factor3,$limit1,$limit2,$limit3);
									$more_result .= $more_bet."\n\n";
									$c+=3;
							}
							
							if(!empty($evt["WXM"][1]["kf"])) {
								$title_exd = "Двойной исход: ";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
									$bet_status_class = "bet-active";
									$ev_finder = "WXM";
									
									$factor1 = $evt["WXM"][1]["kf"];
									$factor2 = $evt["WXM"][2]["kf"];
									$factor3 = $evt["WXM"][3]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									$limit3 = limitBet($factor3,$row["limits"]);
									
									
									
									$more_bet = '
									<div class="outcome-group-wrapper">
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' 1X" data-result="1"><div class="title">1X</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' 12" data-result="2"><div class="title">12</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
										<div class="bet '.$bet_status_class.'" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' X2" data-result="3"><div class="title">X2</div><div class="kef" data-limit="'.$limit3.'">'.$factor3.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$factor3,$limit1,$limit2,$limit3);
									$more_result .= $more_bet."\n\n";
									$c+=3;
								
							}
							
							
							if(!empty($evt["F"][0])) {
								$title_exd = "Фора: ";
								
								$more_result .= '
											
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["F"] as $rowBet) {
									$c = $c + 2;
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="F" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$row["name_1"].' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$row["name_1"].' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="F" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$row["name_2"].' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$row["name_2"].' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
								}
							}
							
							
							if(!empty($evt["T"][0])) {
								$title_exd = "Тотал: ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["T"] as $rowBet) {
									$c = $c + 2;
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									
									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="T" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$name_exd1.' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="T" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$name_exd2.' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
								}
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
									$limit = limitBet($factor,$row["limits"]);
									
									

									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							
							
							
							if(!empty($evt["SCR0"])) {
								unset($more_bet);
								$title_exd = "Точный счет: ";
								$ev_finder = "SCR0";
								
								$res=1;
								foreach($evt[$ev_finder] as $kkk => $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row["limits"]);
									
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' ('.$namesArray["SCR0"]["name_ex"][$kkk].')" data-result="'.$kkk.'" data-lv="'.$namesArray["SCR0"]["name_ex"][$kkk].'"><div class="title">('.$namesArray["SCR0"]["name_ex"][$kkk].')</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
									$res++;
									
									$c++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper" style="flex-wrap: wrap;">
												'.$more_bet.'</div>';
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
									$limit = limitBet($factor,$row["limits"]);
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
											
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
											
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
											
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=3;
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
									$limit = limitBet($factor,$row["limits"]);
								
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							if(!empty($evt["INDTOTAL1"])) {
								$title_exd = "Индивидуальный тотал ".$row["name_1"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL1"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									
									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$name_exd1.' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="INDTOTAL1" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$name_exd2.' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c+=2;
								}
								
								$more_result .= "</li>\n\n";
							}
							
							if(!empty($evt["INDTOTAL2"])) {
								$title_exd = "Индивидуальный тотал ".$row["name_2"].": ";
								$name_exd1 = "Меньше";
								$name_exd2 = "Больше";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["INDTOTAL2"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);

									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$name_exd1.' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="INDTOTAL2" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$name_exd2.' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
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
									$limit = limitBet($factor,$row["limits"]);

									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							if(!empty($evt["TEAM1GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row["name_1"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM1GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row["limits"]);

									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							if(!empty($evt["TEAM2GOALSIN12TIME"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row["name_2"]." забьет в обоих таймах: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "TEAM2GOALSIN12TIME";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row["limits"]);

									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>
												<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							if(!empty($evt["OZANDTOTALUNDER"])) {
								$title_exd = "Обе забьют и тотал меньше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALUNDER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									
									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$name_exd1.' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$name_exd2.' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
											
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c+=2;
								}
							}
							
							if(!empty($evt["OZANDTOTALOVER"])) {
								$title_exd = "Обе забьют и тотал больше: ";
								$name_exd1 = "Да";
								$name_exd2 = "Нет";
								
								$more_result .= '
												<div class="more-bet-title">'.$title_exd.'</div>';
								
								foreach($evt["OZANDTOTALOVER"] as $rowBet) {
									$bet_status_class = "bet-active";
										
									$factor1 = $rowBet[1]["kf"];
									$factor2 = $rowBet[2]["kf"];
									$limit1 = limitBet($factor1,$row["limits"]);
									$limit2 = limitBet($factor2,$row["limits"]);
									
									$more_bet = '
									<div class="outcome-group-wrapper">
									<div class="bet '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row["id"].'" data-lv="'.$rowBet[1]["lv"].'" data-name="'.$title_exd.' '.$name_exd1.' ('.$rowBet[1]["lv"].')" data-result="1"><div class="title">'.$name_exd1.' '.$rowBet[1]["lv"].'</div><div class="kef" data-limit="'.$limit1.'">'.$factor1.'</div></div>
									<div class="bet '.$bet_status_class.'" data-finder="OZANDTOTALUNDER" data-id="'.$row["id"].'" data-lv="'.$rowBet[2]["lv"].'" data-name="'.$title_exd.' '.$name_exd2.' ('.$rowBet[2]["lv"].')" data-result="2"><div class="title">'.$name_exd2.' '.$rowBet[2]["lv"].'</div><div class="kef" data-limit="'.$limit2.'">'.$factor2.'</div></div>
									</div>';
									
									unset($factor1,$factor2,$limit1,$limit2);
									$more_result .= $more_bet."\n\n";
									$c+=2;
								}
								
							}
							
							if(!empty($evt["W1ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row["name_1"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W1ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row["limits"]);
									$more_bet .= '
									
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
											<div class="more-bet-title">'.$title_exd.'</div>
											<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
							
							if(!empty($evt["W2ANDOZ"][1]["kf"])) {
								unset($more_bet);
								$title_exd = $row["name_2"]." победит и обе забьют: ";
								$name_exd["1"] = "Да";
								$name_exd["2"] = "Нет";
								$ev_finder = "W2ANDOZ";
								
								$res=1;
								foreach($evt[$ev_finder] as $rowBet) {
									$factor = $rowBet["kf"];
									$limit = limitBet($factor,$row["limits"]);
									$more_bet .= '
									<div class="bet bet-active" data-finder="'.$ev_finder.'" data-id="'.$row["id"].'" data-name="'.$title_exd.' '.$name_exd[$res].'" data-result="'.$res.'"><div class="title">'.$name_exd[$res].'</div><div class="kef" data-limit="'.$limit.'">'.$factor.'</div></div>
									';
								
									$res++;
								}
								
								$more_result .= '
											<div class="more-bet-title">'.$title_exd.'</div>
											<div class="outcome-group-wrapper">
												'.$more_bet.'</div>';
											
								$c+=2;
							}
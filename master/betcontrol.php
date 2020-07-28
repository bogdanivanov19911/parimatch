<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	
	
				if(!empty($_POST["reject"])) {
					$query97 = $db->query("SELECT * FROM `placed_bet` WHERE `id` = '?i' LIMIT 1", $_POST["reject"]);
					$row97 = $query97->fetch_assoc();
					
					$query714 = $db->query('SELECT `id`,`login`,`balance` FROM `users` WHERE `id` = "?i" LIMIT 1', $row97["user_id"]);
					$row714 = $query714->fetch_assoc();
					
					if($row97["type"] == "2") {
						$factor_c = explode(",",$row97["factor"]);
						
						foreach($factor_c as $value3) {
							$factor_array[] = $value3/100;
						}
						
						$factor = reset($factor_array);
						for($i=1,$c = count($factor_array);$i < $c;++$i) {
							$factor *= $factor_array[$i];
						}
						
						$factor = round($factor,2);
						
						$row97["factor"] = $factor * 100;
					}
					
					if($row97["bet_status"] == 0) {
						$balance = $row714["balance"] + $row97["price"];
						$db->query("UPDATE `placed_bet` SET `bet_status` = '4' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 4) {
						
					} elseif($row97["bet_status"] == 1) {
						$balance = ($row714["balance"] - ($row97["price"] / 100 * $row97["factor"])) + $row97["price"];
						$db->query("UPDATE `placed_bet` SET `bet_status` = '4' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 2) {
						$balance = $row714["balance"] + $row97["price"];
						$db->query("UPDATE `placed_bet` SET `bet_status` = '4' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					}
				}
				
				if(!empty($_POST["winbet"])) {
					$query97 = $db->query("SELECT * FROM `placed_bet` WHERE `id` = '?i' LIMIT 1", $_POST["winbet"]);
					$row97 = $query97->fetch_assoc();
					
					$query714 = $db->query('SELECT `id`,`login`,`balance`, `win_balance` FROM `users` WHERE `id` = "?i" LIMIT 1', $row97["user_id"]);
					$row714 = $query714->fetch_assoc();
					
					if($row97["type"] == "2") {
						$factor_c = explode(",",$row97["factor"]);
						
						foreach($factor_c as $value3) {
							$factor_array[] = $value3/100;
						}
						
						$factor = reset($factor_array);
						for($i=1,$c = count($factor_array);$i < $c;++$i) {
							$factor *= $factor_array[$i];
						}
						
						$factor = round($factor,2);
						
						$row97["factor"] = $factor * 100;
					}
					
					if($row97["bet_status"] == 0) {
						$balance = $row714["win_balance"] + ($row97["price"] / 100 * $row97["factor"]);
						$db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 4) {
						$balance = ($row714["win_balance"] + ($row97["price"] / 100 * $row97["factor"])) - $row97["price"];
						$db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 1) {
						
					} elseif($row97["bet_status"] == 2) {
						$balance = $row714["win_balance"] + ($row97["price"] / 100 * $row97["factor"]);
						$db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					}
				}
				
				if(!empty($_POST["losebet"])) {
					$query97 = $db->query("SELECT * FROM `placed_bet` WHERE `id` = '?i' LIMIT 1", $_POST["losebet"]);
					$row97 = $query97->fetch_assoc();
					
					$query714 = $db->query('SELECT `id`,`login`,`balance` FROM `users` WHERE `id` = "?i" LIMIT 1', $row97["user_id"]);
					$row714 = $query714->fetch_assoc();
					
					if($row97["type"] == "2") {
						$factor_c = explode(",",$row97["factor"]);
						
						foreach($factor_c as $value3) {
							$factor_array[] = $value3/100;
						}
						
						$factor = reset($factor_array);
						for($i=1,$c = count($factor_array);$i < $c;++$i) {
							$factor *= $factor_array[$i];
						}
						
						$factor = round($factor,2);
						
						$row97["factor"] = $factor * 100;
					}
					
					if($row97["bet_status"] == 0) {
						$db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $row97["id"]);
					} elseif($row97["bet_status"] == 4) {
						$balance = $row714["balance"] - $row97["price"];
						$db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 1) {
						$balance = $row714["balance"] - ($row97["price"] / 100 * $row97["factor"]);
						$db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $row97["id"]);
						$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance,$row714["id"]);
					} elseif($row97["bet_status"] == 2) {
						
					}
				}
	
				if(date('N') == 1) {
					$current_week = time() - (date('N') - 1) * 86400;
				} elseif(date('N') == 2) {
					$current_week = time() - (date('N') - 7) * 86400;
				} elseif(date('N') == 3) {
					$current_week = time() - (date('N') - 7) * 86400;
				} elseif(date('N') == 4) {
					$current_week = time() - (date('N') - 7) * 86400;
				} elseif(date('N') == 5) {
					$current_week = time() - (date('N') - 7) * 86400;
				} elseif(date('N') == 6) {
					$current_week = time() - (date('N') - 7) * 86400;
				} elseif(date('N') == 7) {
					$current_week = time() - (date('N') - 7) * 86400;
				}

				if($_GET["filter"] == 1) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (7 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week);
				} elseif($_GET["filter"] == 2) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (14 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (7 * 86400));
				} elseif($_GET["filter"] == 3) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (21 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (14 * 86400));
				} elseif($_GET["filter"] == 4) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (28 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (21 * 86400));
				} elseif($_GET["filter"] == 5) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (35 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (28 * 86400));
				} elseif($_GET["filter"] == 6) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (42 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (35 * 86400));
				} elseif($_GET["filter"] == 7) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (49 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (42 * 86400));
				} elseif($_GET["filter"] == 8) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (56 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (49 * 86400));
				} elseif($_GET["filter"] == 9) {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (61 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week - (56 * 86400));
				} else {
					$dateStart = date ("Y-m-d 00:00:01", $current_week - (7 * 86400));
					$dateLast = date ("Y-m-d 23:59:59",  $current_week);
				}
				
				if($_GET["status"] == 1) {
					$addQuery = "";
				} elseif($_GET["status"] == 2) {
					$addQuery = " AND `bet_status` = '0'";
				} elseif($_GET["status"] == 3) {
					$addQuery = " AND `bet_status` != '0' AND `bet_status` != '4'";
				} elseif($_GET["status"] == 4) {
					$addQuery = " AND `bet_status` = '1'";
				} elseif($_GET["status"] == 5) {
					$addQuery = " AND `bet_status` = '2'";
				} elseif($_GET["status"] == 6) {
					$addQuery = " AND `bet_status` = '4'";
				} else {
					$addQuery = "";
				}
					if(!empty($_GET["user_name"])) {
						$query713 = $db->query('SELECT `id`,`login` FROM `users` WHERE `login` = "?s" LIMIT 1', $_GET["user_name"]);
						$row713 = $query713->fetch_assoc();
						
						if(!empty($row713)) {
							$addQuery .= " AND `user_id` = '".$row713["id"]."'";
						} else {
							$addQuery .= " AND `user_id` = '0'";
						}
					}
				
					$query11 = $db->query('SELECT *,DATE_FORMAT(date_add,"%H:%i / %d.%m.%Y") as `date_add2` FROM `placed_bet` WHERE `date_add` >= "'.$dateStart.'" AND `date_add` <= "'.$dateLast.'"'.$addQuery.' ORDER BY `id` DESC');
					$row11 = $query11->fetch_assoc_array();
					
					foreach($row11 as $key11 => $value11) {
						
						$query712 = $db->query('SELECT `login` FROM `users` WHERE `id` = "'.$value11["user_id"].'" LIMIT 1');
						$row712 = $query712->fetch_assoc();
						
						if($value11["type"] == 2) {
							unset($factor,$bets_array_teams,$bets_array_winner,$result_team_winner);
							$factor = explode(",",$value11["factor"]);
							$bets_array_teams = explode(",",$value11["teams"]);
							$bets_array_winner = explode(",",$value11["winner"]);
							
							$result_team_winner .= '<div class="team_winner_array">Экспресс</div>';
							
							$cic = 0;
							foreach($bets_array_teams as $teams_winner_key => $teams_winner_value) {
								$factor[$cic] = $factor[$cic];
								$result_team_winner .= '<div class="team_winner" style="display: none">'.$teams_winner_value.' <span style="display: inline-block; color: #36ef28;">'.$bets_array_winner[$cic].'</span> ['. format_coef($factor[$cic] / 100) .']</div>';
								$cic++;
							}
							
							
							
							$result = reset($factor);
							for ($i = 1, $c = count($factor);$i < $c;++$i) {

								$result *= $factor[$i] / 100;
							}
							
							$value11["factor"] = round($result,0);
						} else {
							$result_team_winner = $value11["teams"].' - <span style="display: inline-block; color: #36ef28;">'.$value11["winner"].'</span>';
						}
						
						$value11["factor"] = $value11["factor"];
						
						if($value11["bet_status"] == 1) {
							$status = '<span style="color: #36ef28;">Выигрыш</span>';
							$winRes = ((($value11["price"] / 100) * ($value11["factor"] / 100)) - $value11["price"] / 100);
						} elseif($value11["bet_status"] == 2) {
							$status = '<span style="color: #ff2a2a;">Проигрыш</span>';
							$winRes = "-".$value11["price"] / 100;
						} elseif($value11["bet_status"] == 4) {
							$status = '<span style="color: #7474ff;">Возврат</span>';
							$winRes = $value11["price"] / 100;
						} elseif($value11["bet_status"] == 0) {
							$status = '<span style="color: #ffec6b;">В игре</span>';
							$winRes = "";
						}
						
						$stake = $value11["price"] / 100;
						$winRes1 = ((($stake) * ($value11["factor"] / 100)) - $stake);
						
						$body .= '
								<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["user_id"].'
									</td>
									<td>
										'.$row712["login"].'
									</td>
									<td>
										'.$value11["date_add2"].'
									</td>
									<td>
										'.$result_team_winner.'<br /><br />
										<button type="sumbit" class="btn btn2" name="reject" value="'.$value11["id"].'">Возврат</button>
										<button type="sumbit" class="btn btn2" name="winbet" value="'.$value11["id"].'">Выигрыш</button>
										<button type="sumbit" class="btn btn2" name="losebet" value="'.$value11["id"].'">Проигрыш</button>
									</td>
									<td>
										'. format_coef($value11["factor"] / 100) .'
									</td>
									<td>
										'. $stake .'
									</td>
									<td>
										'. $winRes1 .'
									</td>
									<td style="direction: ltr;">
										'.$winRes.'
									</td>
									<td>
										'.$status.'
									</td>
								</tr>
						';
						
						$allStakeTotal += $stake;
						$allWinRes1Total += $winRes1;
						$allWinResTotal += $winRes;
					}
			

					
			$body .= '
							<tr>
							<td colspan="6" style="text-align: left;">
								סה"כ
							</td>
							<td>
								'.$allStakeTotal.'
							</td>
							<td>
								'.$allWinRes1Total.'
							</td>
							<td style="direction: ltr;">
								'.$allWinResTotal.'
							</td>
							<td></td>
					</tr>';
			
			$filterArrays = array(1 => 'Текущая неделя', 'Прошлая неделя', 'Две недели назад', 'Три недели назад', 'Четыре недели назад', 'Пять недель назад', 'Шесть недель назад', 'Семь недель назад', 'Восемь недель назад');
			
			foreach($filterArrays as $keyFilter => $valueFilter) {
				if($_GET["filter"] == $keyFilter) {
					$filterBody .= "<option value='".$keyFilter."' selected>".$valueFilter."</option>";
				} else {
					$filterBody .= "<option value='".$keyFilter."'>".$valueFilter."</option>";
				}
			}
			
			$selectFilter = '
				<select class="filter_select">
					'.$filterBody.'
				</select>';
				
			$statusArrays = array(1 => 'Все ставки', 'Открытые', 'Закрытые', 'Выигрышные', 'Проигрышные', 'Возвраты');
			
			foreach($statusArrays as $keyStatus => $valueStatus) {
				if($_GET["status"] == $keyStatus) {
					$statusBody .= "<option value='".$keyStatus."' selected>".$valueStatus."</option>";
				} else {
					$statusBody .= "<option value='".$keyStatus."'>".$valueStatus."</option>";
				}
			}
			
			$selectStatus = '
				<select class="status_select">
					'.$statusBody.'
				</select>';
			
			
			$user_search = '<div class="user_search"><input type="text" class="user_name" placeholder="Логин" value="'.$_GET['user_name'].'"><div class="user_search_button"></div></div>';

			$tpl = new template('master/template/betcontrol.tpl');
			$tpl->set('{filter}', $selectFilter);
			$tpl->set('{status}', $selectStatus);
			$tpl->set('{user_search}', $user_search);
			$tpl->set('{body}', $body);
			$content = $tpl->parse();
		
?>
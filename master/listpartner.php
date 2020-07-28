<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("Europe/Moscow");
	
	$date = date_create(date("Y-m-d H:i:s"));
	
	$date_now = date_format($date,"Y-m-d H:i:s");
	
	function PageArray($countPage,$actPage,$all,$module) {
		if ($countPage == 0 || $countPage == 1) return FALSE;
		if ($countPage > $all) {
			if($actPage <= 5) {
				for($i = 1; $i <= 10; $i++) {
					if($actPage == $i) {
						$page .= '<span class="active-page">'.$i.'</span> ';
					} else {
						$page .= '<a href="'.$module.'&page='.$i.'">'.$i.'</a> ';
					}
				}
				$page .= "... ";
				$page .= '<a href="'.$module.'&page='.$countPage.'">'.$countPage.'</a>';
			} elseif($actPage + 4 >= $countPage) {
				$page .= '<a href="'.$module.'&page=1">1</a> ';
				$page .= '... ';
				for($j = 1, $k = 9; $j <= 10; $j++, $k--) {
					if($actPage == $countPage - $k) {
						$page .= '<span class="active-page">'.($countPage - $k).'</span> ';
					} else {
						$page .= '<a href="'.$module.'&page='.($countPage - $k).'">'.($countPage - $k).'</a> ';
					}
				}
			} else {
				$oser = floor($all/2)-1;
				$page .= '<a href="'.$module.'&page=1">1</a> ';
				$page .= '... ';
				for($i = 1,$k = $oser; $i <= $all-1; $i++, $k--) {
					if($actPage == $actPage - $k) {
						$page .= '<span class="active-page">'.($actPage - $k).'</span> ';
					} else {
						$page .= '<a href="'.$module.'&page='.($actPage - $k).'">'.($actPage - $k).'</a> ';
					}
				}
				$page .= '... ';
				$page .= '<a href="'.$module.'&page='.$countPage.'">'.$countPage.'</a>';
			}
		} else {
			for($i = 1; $i < $countPage + 1; $i++) {
				if($actPage == $i) {
					$page .= '<span class="active-page">'.$i.'</span> ';
				} else {
					$page .= '<a href="'.$module.'&page='.$i.'">'.$i.'</a> ';
				}
			}
		}
		
		if ($actPage > 1) {
			$left_navigation = '<a href="'.$module.'&page='.($actPage-1).'" id="prev">Назад</a>';
		} else {
			$left_navigation = '<span id="prev">Назад</span>';
		}
		if($actPage < $countPage) {
			$right_navigation = '<a href="'.$module.'&page='.($actPage+1).'" id="next">Вперед</a>';
		} else {
			$right_navigation = '<span id="next">Вперед</span>';
		}
		
		$nav_selector = new template('admin/template/page.tpl');
		$nav_selector->set('{prev}', "");
		$nav_selector->set('{page}', $page.'<div class="user_search" style="margin-left: 130px;"><input type="text" class="user_name" placeholder="Имя игрока" value=""><div class="user_search_button"></div></div>');
		$nav_selector->set('{next}', "");
		return $nav_selector->parse();
	}
	
	if($_GET["option"] == "edit" and !empty($_GET["id"])) {
		$query = $db->query('SELECT *,INET_NTOA(ip) FROM `users` WHERE `id` = "?i"', $_GET['id']);
		$user2 = $query->fetch_assoc();
		
		
		
		if(!empty($user2["id"])) {
			$queryReferalList = $db->query('SELECT * FROM `referrals` WHERE `referrals_id` = "?i"', $user2["id"]);
			$rowReferalList = $queryReferalList->fetch_assoc_array();
			
			foreach($rowReferalList as $keyM1 => $valM1) {
				$usersListAllMain .= $valM1["user_id"].",";
			}
			$usersListAllMain = chop($usersListAllMain, ",");
			
			if(!empty($usersListAllMain)) {
				
				if($user2["partner"] == 2) {
					$query = $db->query("SELECT * FROM `placed_bet` WHERE `user_id` IN (".$usersListAllMain.") AND `bet_status` != '0'");
					$row = $query->fetch_assoc_array();
					
					foreach($row as $keyBalanceList => $valueBalanceList) {
						if($valueBalanceList["type"] == 2) {
							unset($factor,$result,$bet_result,$factor_array,$factor_c);
							$bet_result = explode(",",$valueBalanceList["bet_result"]);
							
							if($valueBalanceList["rate"] == $valueBalanceList["bet_result"]) {
								$factor = explode(",",$valueBalanceList["factor"]);
								$result = reset($factor);
								for ($i = 1, $c = count($factor);$i < $c;++$i) {
									$result *= $factor[$i] / 100;
								}
								$valueBalanceList["factor"] = round($result,2);
							} elseif(in_array("REJECT", $bet_result)) {
								$factor_c = explode(",",$valueBalanceList["factor"]);
								
								$c = 0;
								foreach($bet_result as $value4) {
									if($value4 == "REJECT") {
										$factor_c[$c] = "100";
									}
									$c++;
								}
								
								foreach($factor_c as $value5) {
									$factor_array[] = $value5/100;
								}
								
								$factor = reset($factor_array);
								for($i=1,$c = count($factor_array);$i < $c;++$i) {
									$factor *= $factor_array[$i];
								}
								
								$valueBalanceList["factor"] = round($factor,2);
							}
						}
						if($valueBalanceList["bet_status"] == 2) {
							$loseUsers[] = $valueBalanceList["price"] / 100;
						} elseif($valueBalanceList["bet_status"] == 1) {
							$winUsers[] = ((($valueBalanceList["price"] / 100) * ($valueBalanceList["factor"] / 100)) - $valueBalanceList["price"] / 100);
						}
					}
					
					if(!empty($winUsers)) {
						$winUsersBet = "0";
						for($i=0,$c = count($winUsers);$i < $c;++$i) {
							$winUsersBet += $winUsers[$i];
						}
					} else {
						$winUsersBet = "0";
					}
					
					if(!empty($loseUsers)) {
						$loseUsersBet = "0";
						for($i=0,$c = count($loseUsers);$i < $c;++$i) {
							$loseUsersBet += $loseUsers[$i];
						}
					} else {
						$loseUsersBet = "0";
					}
					
					
				} elseif($user2["partner"] == 3) {
					$query = $db->query("SELECT * FROM `payment` WHERE `id_user` IN (".$usersListAllMain.") AND (`status` = 1 OR `status` = 5) AND `hide` IS NULL");
					$row = $query->fetch_assoc_array();
					
					foreach($row as $keyBalanceList => $valueBalanceList) {
						$depUsers[] = $valueBalanceList["price"];
					}
					
					if(!empty($depUsers)) {
						$depUsersAll = "0";
						for($i=0,$c = count($depUsers);$i < $c;++$i) {
							$depUsersAll += $depUsers[$i];
						}
					} else {
						$depUsersAll = "0";
					}
					
					if($user2["liveSpread"] == 100) {
						$depUsers = $depUsersAll;
					} else {
						$depUsers = $depUsersAll * ($user2["liveSpread"] / 100);
					}
				}
			}
			if($user2["partner"] == 1) {
				$balance_AG = $user2["balance_agent"] / 100;
			} elseif($user2["partner"] == 2) {
				$revshareperc = $user2["revshare"] / 100;
				
				$balance_AG = round($user2["balance_agent"] / 100 + ($loseUsersBet - $winUsersBet) * $revshareperc, 0);
			} elseif($user2["partner"] == 3) {
				$balance_AG = round($user2["balance_agent"] / 100 + $depUsers, 0);
			}
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		$dataS = array();
		
		$dtexplodeStart = explode("/",$_GET["datestart"]);
		$dayStart = $dtexplodeStart[1];
		$monsStart = $dtexplodeStart[0];
		$yearStart = $dtexplodeStart[2];
		
		$dtexplodeEnd = explode("/",$_GET["dateend"]);
		$dayEnd = $dtexplodeEnd[1];
		$monsEnd = $dtexplodeEnd[0];
		$yearEnd = $dtexplodeEnd[2];

		if(!empty($_GET["datestart"])) {
			$dateStart = $yearStart."-".$monsStart."-".$dayStart." 00:00:01";
			$dateLast = $yearEnd."-".$monsEnd."-".$dayEnd." 23:59:59";
			$dateStart3 = $yearStart."-".$monsStart."-".$dayStart;
			$dateLast3 = $yearEnd."-".$monsEnd."-".$dayEnd;
		} else {
			$date7 = strtotime('-6 days');
			$date7 = date('Y-m-d', $date7);
			
			$dateStart = $date7. " 00:00:01";
			$dateLast = date("Y-m-d 23:59:59");
			$dateStart3 = $date7;
			$dateLast3 = date("Y-m-d");
		}
		
		if(!empty($_GET["streams"]) AND $_GET["streams"] != 0) {
			$streams = preg_replace('/[^0-9]/ui', '', $_GET["streams"]);
			$addQueryUsersList .= " AND `stream_id` = '".$streams."' ";
		}
		
		if(!empty($_GET["sub1"])) {
			$sub1 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub1"]);
			$subAdd .= " AND `s1` = '".$sub1."' ";
		}
		
		if(!empty($_GET["sub2"])) {
			$sub2 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub2"]);
			$subAdd .= " AND `s2` = '".$sub2."' ";
		}
		
		if(!empty($_GET["sub3"])) {
			$sub3 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub3"]);
			$subAdd .= " AND `s3` = '".$sub3."' ";
		}
		
		if(!empty($_GET["sub4"])) {
			$sub4 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub4"]);
			$subAdd .= " AND `s4` = '".$sub4."' ";
		}
		
		if(!empty($_GET["sub5"])) {
			$sub5 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub5"]);
			$subAdd .= " AND `s5` = '".$sub5."' ";
		}
		
		if(!empty($sub1) OR !empty($sub2) OR !empty($sub3) OR !empty($sub4) OR !empty($sub5)) {
			$statsListQuery = $db->query('SELECT `id` FROM `stats` WHERE `id` != 0 '.$subAdd);
			$statsList = $statsListQuery->fetch_assoc_array();
			
			foreach($statsList as $keyStat => $valueStat) {
				$refferals_stat .= $valueStat["id"].",";
			}
			$refferals_stat = chop($refferals_stat, ",");
			
			if(!empty($refferals_stat)) {
				$addQueryUsersList .= " AND `stat_id` IN (".$refferals_stat.") ";
			} else {
				$addQueryUsersList .= " AND `stat_id` = '0'";
			}
		}
		
		$queryUsersList = $db->query('SELECT `user_id` FROM `referrals` WHERE `referrals_id` = "?i"'.$addQueryUsersList, $user2["id"]);
		$rowUsersList = $queryUsersList->fetch_assoc_array();
		
		foreach($rowUsersList as $keyUsersList => $valueUsersList) {
			$usersListAll .= $valueUsersList["user_id"].",";
		}
		$usersListAll = chop($usersListAll, ",");
		
		if(!empty($usersListAll)) {
			$query7 = $db->query("SELECT *,DATE_FORMAT(date_add ,'%Y-%m-%d') as `date_add2` FROM `placed_bet` WHERE `user_id` IN (".$usersListAll.") AND `bet_status` != '0' AND `date_add` >= '".$dateStart."' AND `date_add` <= '".$dateLast."'");
			$row7 = $query7->fetch_assoc_array();

			$dtStart = strtotime($dateStart);
			$dtLast = strtotime($dateLast);
			$caseStatsQuery = $db->query("SELECT (sum(lost) - sum(win)) as sum, DATE(FROM_UNIXTIME(created_at)) as created FROM `case_stats` WHERE `user_id` IN (".$usersListAll.") AND `created_at` BETWEEN $dtStart AND $dtLast GROUP BY created");
			$caseStats = $caseStatsQuery->fetch_assoc_array();
			$cases = array();

			if (!empty($caseStats)) {
				foreach ($caseStats as $caseStat) {
					$cases[$caseStat['created']] = round($caseStat['sum']);
				}
			}

			foreach($row7 as $key7 => $value7) {
				unset($loseUser,$winUser);

					if($value7["type"] == 2) {
						unset($factor,$result,$bet_result,$factor_array,$factor_c);
						$bet_result = explode(",",$value7["bet_result"]);
						
						if($value7["rate"] == $value7["bet_result"]) {
							$factor = explode(",",$value7["factor"]);
							$result = reset($factor);
							for ($i = 1, $c = count($factor);$i < $c;++$i) {
								$result *= $factor[$i] / 100;
							}
							$value7["factor"] = round($result,0);
						} elseif(in_array("REJECT", $bet_result)) {
							$factor_c = explode(",",$value7["factor"]);
							
							$c = 0;
							foreach($bet_result as $value4) {
								if($value4 == "REJECT") {
									$factor_c[$c] = "100";
								}
								$c++;
							}
							
							foreach($factor_c as $value5) {
								$factor_array[] = $value5/100;
							}
							$factor = reset($factor_array);
							for($i=1,$c = count($factor_array);$i < $c;++$i) {
								$factor *= $factor_array[$i];
							}
							
							$value7["factor"] = round($factor,0);
						}
					}
				
				
				
				
				if($value7["bet_status"] == 2) {
					$loseUser = $value7["price"] / 100;
				} elseif($value7["bet_status"] == 1) {
					$winUser = ((($value7["price"] / 100) * ($value7["factor"] / 100)) - $value7["price"] / 100);
				}
				
				$dataS[$value7["date_add2"]]["winbet"] += $winUser;
				$dataS[$value7["date_add2"]]["losebet"] += $loseUser;
			}
		}
		
		if(!empty($_GET["streams"]) AND $_GET["streams"] != 0) {
			$query = $db->query('SELECT * FROM `streams` WHERE `user_id` = "?i" AND `id` = "?s"', $user2["id"],$_GET["streams"]);
		} else {
			$query = $db->query('SELECT * FROM `streams` WHERE `user_id` = "?i"', $user2["id"]);
		}
		
		$row = $query->fetch_assoc_array();
		
		foreach($row as $key => $value) {
			$statID .= $value["id"].",";
		}
		
		$queryS = $db->query('SELECT * FROM `streams` WHERE `user_id` = "?i"', $user2["id"]);
		$rowS = $queryS->fetch_assoc_array();
		$optionStreams .= '<option value="0">All streams</option>';
		
		$v=1;
		foreach($rowS as $keyS => $valueS) {
			if($v == $_GET["streams"]) {
				$optionStreams .= '<option value="'.$valueS["id"].'" selected>'.$valueS["name"].'</option>';
			} else {
				$optionStreams .= '<option value="'.$valueS["id"].'">'.$valueS["name"].'</option>';
			}
			$v++;
		}
		
		$statID = chop($statID, ",");
		
		if(!empty($statID)) {
			if(!empty($_GET["sub1"])) {
				$s1 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub1"]); 
				$addQuery .= " AND `s1` = '".$s1."' ";
			}
			if(!empty($_GET["sub2"])) {
				$s2 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub2"]); 
				$addQuery .= " AND `s2` = '".$s2."' ";
			}
			if(!empty($_GET["sub3"])) {
				$s3 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub3"]); 
				$addQuery .= " AND `s3` = '".$s3."' ";
			}
			if(!empty($_GET["sub4"])) {
				$s4 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub4"]); 
				$addQuery .= " AND `s4` = '".$s4."' ";
			}
			if(!empty($_GET["sub5"])) {
				$s5 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["sub5"]); 
				$addQuery .= " AND `s5` = '".$s5."' ";
			}
			
			$query2 = $db->query('SELECT * FROM `stats` WHERE `stream_id` IN ('.$statID.') AND `date` >= "'.$dateStart3.'" AND `date` <= "'.$dateLast3.'" '.$addQuery);
			$row2 = $query2->fetch_assoc_array();
			
			foreach($row2 as $key2 => $value2) {
				
				if(empty($dataS[$value2["date"]]["stat_foreach"])) {
					$dataS[$value2["date"]]["stat_foreach"] = 0;
				}
				
				$dataS[$value2["date"]]["visits"] += $value2["visits"];
				$dataS[$value2["date"]]["visits_all"] += $value2["visits_all"];
				$dataS[$value2["date"]]["fdps"] += $value2["fdps"];
				$dataS[$value2["date"]]["fdps_count"] += $value2["fdps_count"];
				
				if(empty($dataS[$value2["date"]]["registration"])) {
					$dataS[$value2["date"]]["registration"] = 0;
				}
				if(empty($dataS[$value2["date"]]["deposits_count"])) {
					$dataS[$value2["date"]]["deposits_count"] = 0;
				}
				
				$query3 = $db->query('SELECT * FROM `referrals` WHERE `stat_id` = "?i"', $value2["id"]);
				$row3 = $query3->fetch_assoc_array();
				
				if(!empty($row3[0])) {
					foreach($row3 as $key3 => $value3) {
						$usersList .= $value3["user_id"].",";
					}
					$usersList = chop($usersList, ",");
					
					if(!empty($usersList)) {
						$query4 = $db->query('SELECT * FROM `users` WHERE `id` IN ('.$usersList.')');
						$row4 = $query4->fetch_assoc_array();
						
						foreach($row4 as $key4 => $value4) {
							$dataS[$value2["date"]]["registration"] += 1;
						}
						
						$dateStart2 = $value2["date"]." 00:00:01";
						$dateLast2 = $value2["date"]." 23:59:59";
						
						
						if($dataS[$value2["date"]]["stat_foreach"] == 0) {
							$query5 = $db->query('SELECT * FROM `payment` WHERE `id_user` IN ('.$usersListAll.') AND `date_pay` >= "'.$dateStart2.'" AND `date_pay` <= "'.$dateLast2.'" AND (`status` = 1 OR `status` = 5) AND `hide` IS NULL');
							$row5 = $query5->fetch_assoc_array();
							
							$i=0;
							foreach($row5 as $key5 => $value6) {
								$dataS[$value2["date"]]["deposits_summ"] += $value6["price"];
								$dataS[$value2["date"]]["deposits_count"] += 1;
								
								if(!empty($value6["price"]) AND $i == 0) {
									$dataS[$value2["date"]]["deposits_unic"] += 1;
									$i=1;
								}
							}
							
							$dataS[$value2["date"]]["stat_foreach"] = 1;
						}
					}
				}
				unset($usersList);
			}
		}
		
		
		$body1 .= '
					<tr class="tabletit">
						<td>
							Дата
						</td>
						<td>
							Переходы / Уник
						</td>
						<td>
							Конверсии <br>
							<hr>
							<span class="span4"><span>Регистрации</span> <span>Акт Игр</span> <span>Новые деп.</span> <span>Всего деп.</span></span>
						</td>
						<td>
							Финансы<br>
							<hr>
							<span class="span4"><span>Сумма деп.</span> <span>Выигрыш</span> <span>Проигрыш</span> <span>Результат</span></span>
						</td>
						<td style="width: 120px;">
							Доход <br>
							<hr>
							<span class="span4"><span style="width: 49%">БК</span> <span style="width: 49%">Игры</span></span>
						</td>
						<td>
							Итоговый доход
						</td>
					</tr>';

		foreach($dataS as $keyDataS => $valueDataS) {
			$newDataS[$keyDataS] = $keyDataS;
		}
		
		array_multisort($newDataS, SORT_DESC, SORT_STRING, $dataS);

		$begin = new DateTime($dateStart);
		$end = new DateTime($dateLast);

		for($dt = $end; $dt >= $begin; $dt->modify('-1 day')){
			$key10 = $dt->format("Y-m-d");

			if (isset($dataS[$key10])) {
				$value10 = $dataS[$key10];
			} else {
				$value10 = array();
			}

			if (isset($cases[$key10])) {
				$caseSum = $cases[$key10];
			} else {
				$caseSum = 0;
			}

			if(empty($value10['deposits_unic'])) {
				$value10['deposits_unic'] = 0;
			}
			
			if(empty($value10['visits'])) {
				$value10['visits'] = 0;
			}
			
			if(empty($value10['visits_all'])) {
				$value10['visits_all'] = 0;
			}
			
			if(empty($value10['registration'])) {
				$value10['registration'] = 0;
			}
			
			if(empty($value10['deposits_count'])) {
				$value10['deposits_count'] = 0;
			}
			
			if(empty($value10['fdps'])) {
				$value10['fdps'] = 0;
			}
			
			if(empty($value10['winbet'])) {
				$value10['winbet'] = 0;
			}
			
			if(empty($value10['losebet'])) {
				$value10['losebet'] = 0;
			}
			
			if(empty($value10['deposits_summ'])) {
				$value10['deposits_summ'] = 0;
			}
			
			$resultstt = $value10['losebet'] - $value10['winbet'];
			
			if($user2["partner"] == 1) {
				$itogoviy_dohod = $value10['fdps'];
				$komission = "0.00";
			} elseif($user2["partner"] == 2) {
				$revshareperc = $user2["revshare"] / 100;
				$itogoviy_dohod = $resultstt * $revshareperc;
				$komission = $resultstt * $revshareperc;
			} elseif($user2["partner"] == 3) {
				$itogoviy_dohod = $value10['deposits_summ'] * ($user2["liveSpread"] / 100);
			}

			$bkSum = $itogoviy_dohod;
			$allSum = $bkSum + $caseSum;
			$body1 .= "
				<tr>
					<td>
						".$key10."
					</td>
					<td>
						".$value10['visits_all']." / ".$value10['visits']."
					</td>
					<td>
						<span class='span4'><span>".$value10['registration']."</span> <span>".$value10['fdps_count']."</span> <span>".$value10['fdps_count']."</span> <span>".$value10['deposits_count']."</span></span>
					</td>
					<td>
						<span class='span4'><span>". round($value10['deposits_summ'],0) ."</span> <span>". round($value10['winbet'],0) ."</span> <span>". round($value10['losebet'],0) ."</span> <span>". round($resultstt,0) ."</span></span>
					</td>
					<td>
						<span class='span4'><span style='width: 49%'>" . intval($bkSum). "</span> <span style='width: 49%'>" . intval($caseSum). "</span></span>
					</td>
					<td>
						". round($allSum,0) ." ₽
					</td>
				</tr>
			";
			$visitsAll += $value10['visits'];
			$visitsAll2 += $value10['visits_all'];
			$registrationAll += $value10['registration'];
			$deposits_unicAll += $value10['deposits_unic'];
			$deposits_countAll += $value10['deposits_count'];
			$deposits_summAll += $value10['deposits_summ'];
			$winbetAll += $value10['winbet'];
			$losebetAll += $value10['losebet'];
			$fdpsAll += $value10['fdps'];
			$fdpsCountAll += $value10['fdps_count'];
			$komissionAll += $komission;
			$itogoviy_dohodAll += $allSum;
			$caseSumAll += $caseSum;
			$bkSumAll += $bkSum;
			$haveBody = 1;
			unset($resultstt);
		}
		
		$resultsAll = $losebetAll - $winbetAll;

		if($haveBody == 1) {
			$body1 .= '
						<tr class="tabletit">
							<td>
								Итого
							</td>
							<td>
								'.$visitsAll2.' / '.$visitsAll.'
							</td>
							<td>
								<span class="span4"><span>'.$registrationAll.'</span> <span>'.$fdpsCountAll.'</span> <span>'.$fdpsCountAll.'</span> <span>'.$deposits_countAll.'</span></span>
							</td>
							<td>
								<span class="span4"><span>'.$deposits_summAll.'</span><span>'. round($winbetAll,0) .'</span> <span>'. round($losebetAll,0) .'</span> <span>'. round($resultsAll,0) .'</span></span>
							</td>
							<td>
								<span class="span4"><span style="width: 49%">' . intval($bkSumAll). '</span> <span style="width: 49%">' . intval($caseSumAll). '</span></span>
							</td>
							<td>
								'. round($itogoviy_dohodAll,0) .' ₽
							</td>
						</tr>';
		} else {
			$body1 .= '
						<tr>
							<td colspan="5" style="background:#131313;color:#fff;padding:20px;text-align:center;">
								Нет данных!
							</td>
						</tr>';
		}
		
		
		
			$tpl = new template('master/template/stats.tpl');
			$tpl->set('{body1}', $body1);
			$tpl->set('{streams}', $optionStreams);
			$tpl->set('{sub1}', $_GET["sub1"]);
			$tpl->set('{sub2}', $_GET["sub2"]);
			$tpl->set('{sub3}', $_GET["sub3"]);
			$tpl->set('{sub4}', $_GET["sub4"]);
			$tpl->set('{sub5}', $_GET["sub5"]);
			$tpl->set('{id}', $user2["id"]);
			$tpl->set('{balance_partner}', $balance_AG);
			
			
			if(!empty($_GET["datestart"])) {
				$tpl->set('{datepickerjs}', " $('#date_range').datepicker('setDate', ['".$_GET["datestart"]."', '".$_GET["dateend"]."']);");
			} else {
				$tpl->set('{datepickerjs}', " $('#date_range').datepicker('setDate', 'y-m-d');");
			}
			
			$tpl->set('{startDate}', $_GET["datestart"]);
			$tpl->set('{endDate}', $_GET["dateend"]);
			
			$content = $tpl->parse();
		
	} elseif($_GET["option"] == "ref" and !empty($_GET["id"])) { 
	
		$queryUsersList = $db->query('SELECT `user_id` FROM `referrals` WHERE `referrals_id` = "?i"', $_GET["id"]);
		$rowUsersList = $queryUsersList->fetch_assoc_array();
		
		foreach($rowUsersList as $keyUsersList => $valueUsersList) {
			$usersListAll .= $valueUsersList["user_id"].",";
		}
		$usersListAll = chop($usersListAll, ",");
		
		if(!empty($usersListAll)) {
				$query11 = $db->query("SELECT COUNT(id) FROM `users` WHERE `id` IN (".$usersListAll.")");
				$row11 = $query11->fetch_assoc();
				$number_news = $row11["COUNT(id)"];
				$count_news = 100;
				$all_page = @ceil($number_news/$count_news);	/// Всего страниц
				if(isset($_GET['page'])) $page = intval($_GET['page']); /// Текущая страница
				if(empty($_GET['page'])) $page = 1; /// Проверка, где вы находитесь
				if($page == 0) $page = 1;
		
		
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
			
				$query11 = $db->query('SELECT * FROM `users` WHERE `id` IN ('.$usersListAll.') ORDER BY `id` DESC LIMIT '.$count_news.' OFFSET '.(($page-1)*$count_news));
				
				$row11 = $query11->fetch_assoc_array();
				
				foreach($row11 as $key11 => $value11) {
						unset($refferals);
						
						if($value11["status"] == 1) {
							$checkStatusUser = '<input type="checkbox" name="onoffswitch" value="'.$value11["id"].'">';
						} else {
							$checkStatusUser = '<input type="checkbox" checked="checked" name="onoffswitch" value="'.$value11["id"].'">';
						}
						
						$queryUsersList = $db->query('SELECT COUNT(id) FROM `referrals` WHERE `referrals_id` = "?i"', $value11["id"]);
						$sda123 = $queryUsersList->fetch_assoc();
						$refferals = $sda123["COUNT(id)"];
						$body .= '
								<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["login"].'
									</td>
									<td>
										<a href="/master.php?do=listbet&filter=&status=1&user_name='.$value11["login"].'">
											<div class="btn btn-primary btn-primaryNew">Просмотр</div>
										</a>
									</td>
									<td>
										'.$checkStatusUser.'
									</td>
									
									<td>
										<a href="/master.php?do=listmaster&option=edit&id='.$value11["id"].'">
											<div class="btn btn-primary right btn-primaryNew">Просмотр</div>
										</a>
									</td>
								</tr>
						';
				}
				
			if(empty($_POST)) {
				$pages = PageArray($all_page,$page,10,'/master.php?do=listpartner&option=ref&id='.$_GET["id"]);
				
				$tpl = new template('master/template/partner-view-user.tpl');
				$tpl->set('{body}', $body);
				$tpl->set('{page}', $pages);
				$content = $tpl->parse();
			}
		}
	} elseif($_GET["option"] == "setting" and !empty($_GET["id"])) {
				$query11 = $db->query("SELECT * FROM `users` WHERE `id` = '?s'",$_GET["id"]);
				$row = $query11->fetch_assoc();
			if(!empty($_POST['document']) OR !empty($_POST['percentdep']) OR !empty($_POST['redirectdom']) OR !empty($_POST['redirectmirror'])) {
				$query = $db->query('UPDATE `users` SET `comment_am` = "?s", `liveSpread` = "?i", `revshare` = "?i", `partner` = "?i", `redirect` = "?s", `mirror` = "?s" WHERE `id` = "?i"', $_POST["document"], $_POST["percentdep"], $_POST["percentrevshare"], $_POST["typepartner"], $_POST['redirectdom'], $_POST['redirectmirror'], $_GET["id"]);
				header('Location: /master.php?do=listpartner&option=setting&id='.$_GET["id"]);
			}
		
			if(empty($_POST)) {
				$typePartner .= '<select name="typepartner">';
					if($row["partner"] == 1) {
						$typePartner .= '<option value="1" selected="">CPA</option>
						<option value="2">Revshare</option>
						<option value="3">Deposits</option>';
					} elseif($row["partner"] == 2) {
						$typePartner .= '<option value="1">CPA</option>
						<option value="2" selected="">Revshare</option>
						<option value="3">Deposits</option>';
					} elseif($row["partner"] == 3) {
						$typePartner .= '<option value="1">CPA</option>
						<option value="2">Revshare</option>
						<option value="3" selected="">Deposits</option>';
					}
				$typePartner .= '</select>';
				
				$mixSpread .= '<select name="mixSpread">';
					for($i=14;$i<=100;$i++) {
						if($i == $row["mixSpread"]) {
							$mixSpread .= '<option value="'.$i.'" selected>'.$i.'</option>';
						} else {
							$mixSpread .= '<option value="'.$i.'">'.$i.'</option>';
						}
					}
				$mixSpread .= '</select>';
				
				$tpl = new template('master/template/partner-edit.tpl');
				$tpl->set('{login}', $row["login"]);
				$tpl->set('{name_user}', $row["name"]);
				$tpl->set('{percentdep}', $row["liveSpread"]);
				$tpl->set('{percentrevshare}', $row["revshare"]);
				$tpl->set('{typepartner}', $typePartner);
				$tpl->set('{redirectdom}', $row["redirect"]);
				$tpl->set('{redirectmirror}', $row["mirror"]);
				$tpl->set('{autopayment}', $row["auto_payment"] / 100);
				$tpl->set('{bonus_percent}', $bonusPercent);
				$tpl->set('{document}', $row["comment_am"]);
				$tpl->set('{mixSpread}', $mixSpread);
				$content = $tpl->parse();
			}
	} else {
				$query11 = $db->query("SELECT COUNT(id) FROM `users` WHERE `moderator` = 9");
				$row11 = $query11->fetch_assoc();
				$number_news = $row11["COUNT(id)"];
				$count_news = 100;
				$all_page = @ceil($number_news/$count_news);	/// Всего страниц
				if(isset($_GET['page'])) $page = intval($_GET['page']); /// Текущая страница
				if(empty($_GET['page'])) $page = 1; /// Проверка, где вы находитесь
				if($page == 0) $page = 1;
		
		
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
			
				if(!empty($_GET["login"])) {
					$query11 = $db->query('SELECT * FROM `users` WHERE `login` LIKE "%?s%" OR `id` LIKE "%?s%" OR `email` LIKE "%?s%" AND `moderator` = 9 ORDER BY `id` DESC', $_GET["login"], $_GET["login"], $_GET["login"]);
				} else {
					$query11 = $db->query('SELECT * FROM `users` WHERE `moderator` = 9 ORDER BY `id` DESC LIMIT '.$count_news.' OFFSET '.(($page-1)*$count_news));
				}
				
				$row11 = $query11->fetch_assoc_array();
				
				foreach($row11 as $key11 => $value11) {
						unset($refferals);
						
						if($value11["status"] == 1) {
							$checkStatusUser = '<input type="checkbox" name="onoffswitch" value="'.$value11["id"].'">';
						} else {
							$checkStatusUser = '<input type="checkbox" checked="checked" name="onoffswitch" value="'.$value11["id"].'">';
						}
						
						$queryUsersList = $db->query('SELECT COUNT(id) FROM `referrals` WHERE `referrals_id` = "?i"', $value11["id"]);
						$sda123 = $queryUsersList->fetch_assoc();
						$refferals = $sda123["COUNT(id)"];
						$body .= '
								<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["login"].'
									</td>
									<td>
										'.$refferals.'
									</td>
									<td>
										'.$checkStatusUser.'
									</td>
									
									<td>
										<a href="/master.php?do=listpartner&option=edit&id='.$value11["id"].'">
											<div class="btn btn-primary right btn-primaryNew">Просмотр</div>
										</a>
									</td>
								</tr>
						';
				}
				
			if(empty($_POST)) {
				$pages = PageArray($all_page,$page,10,'/master.php?do=listpartner');
				
				$tpl = new template('master/template/partner-view.tpl');
				$tpl->set('{body}', $body);
				$tpl->set('{page}', $pages);
				$content = $tpl->parse();
			}
				
	}
	
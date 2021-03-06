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
		
		$query = $db->query('SELECT * FROM `users` WHERE `id` = "?i"', $_GET['id']);
		$row = $query->fetch_assoc();
		$row_check = $db->getAffectedRows();
		
		if($row_check == 1) {
			if(empty($_POST)) {
				
				$query52 = $db->query("SELECT `price` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` = '0'", $_GET["id"]);
				while($row52 = $query52->fetch_assoc()) {
					$unresolved[] = $row52["price"];
				}
				
				if(!empty($unresolved)) {
					$user["unresolved"] = "";
					for($i=0,$c = count($unresolved);$i < $c;++$i) {
						$user["unresolved"] += $unresolved[$i];
					}
				} else {
					$user["unresolved"] = "0";
				}
				
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
				
				$row["balance"] = $row["balance"] / 100;
				$tpl = new template('admin/template/user-edit.tpl');
				$tpl->set('{id}', $row["id"]);
				$tpl->set('{login}', $row["login"]);
				$tpl->set('{name_user}', $row["name"]);
				$tpl->set('{surname}', $row["surname"]);
				$tpl->set('{email}', $row["email"]);
				$tpl->set('{age}', $row["age"]);
				$tpl->set('{balance}', $row["balance"]);
				$tpl->set('{percentdep}', $row["liveSpread"]);
				$tpl->set('{percentrevshare}', $row["revshare"]);
				$tpl->set('{typepartner}', $typePartner);
				$tpl->set('{redirectdom}', $row["redirect"]);
				$tpl->set('{redirectmirror}', $row["mirror"]);
				$tpl->set('{autopayment}', $row["auto_payment"] / 100);
				$tpl->set('{bonus_percent}', $bonusPercent);
				$tpl->set('{document}', $row["comment_am"]);
				$tpl->set('{liveSpread}', $liveSpread);
				$tpl->set('{prematchSpread}', $prematchSpread);
				$tpl->set('{mixSpread}', $mixSpread);
				$tpl->set('{balance_u}', $user["unresolved"] / 100);
				$tpl->set('{history}', $line_bets);
				$content = $tpl->parse();
			}
		} else {
			$content = '<div class="error">Пользователя не существует!</div>';
		}
		
	} else {
		
				$query11 = $db->query("SELECT COUNT(id) FROM `users`");
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
						$query11 = $db->query('SELECT * FROM `users` WHERE `login` LIKE "%?s%" OR `id` LIKE "%?s%" OR `email` LIKE "%?s%" ORDER BY `id` DESC', $_GET["login"], $_GET["login"], $_GET["login"]);
					} else {
						$query11 = $db->query('SELECT * FROM `users` ORDER BY `id` DESC LIMIT '.$count_news.' OFFSET '.(($page-1)*$count_news));
					}
					
					$row11 = $query11->fetch_assoc_array();
					foreach($row11 as $key11 => $value11) {
						$openBetuser = 0;
						$closeBetuser = 0;
						unset($unresolvedUser,$TurneoverUser,$winUser,$loseUser,$balanceWeekNow,$userBonusNow);
						$query = $db->query("SELECT `id`,`id_bets`,`rate`,`price`,`user_id`,`type`,`factor`,`bet_result`,`bet_status` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` = '0' AND `date_add` >= '".$dateStart."' AND `date_add` <= '".$dateLast."'", $value11["id"]);
						while($row23 = $query->fetch_assoc()) {
							$unresolvedUser[] = $row23["price"]/100;
							$openBetuser++;
						}
						
						$query2 = $db->query("SELECT `id`,`price`,`bet_status` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` != '0' AND `date_add` >= '".$dateStart."' AND `date_add` <= '".$dateLast."'", $value11["id"]);
						
						while($row24 = $query2->fetch_assoc()) {
							$TurneoverUser[] = $row24["price"]/100;
							$closeBetuser++;
						}
						
						$query3 = $db->query("SELECT `id`,`price`,`factor`,`bet_status`,`type` FROM `placed_bet` WHERE `user_id` = '?i' AND `bet_status` != '0' AND `date_add` >= '".$dateStart."' AND `date_add` <= '".$dateLast."'", $value11["id"]);
						
						while($row25 = $query3->fetch_assoc()) {
							if($row25["type"] == 2) {
								unset($factor,$result);
								$factor = explode(",",$row25["factor"]);
								$result = reset($factor);
								for ($i = 1, $c = count($factor);$i < $c;++$i) {
									$result *= $factor[$i] / 100;
								}
								$row25["factor"] = round($result,0);
							}
							if($row25["bet_status"] == 2) {
								$loseUser[] = $row25["price"] / 100;
							} elseif($row25["bet_status"] == 1) {
								$winUser[] = ((($row25["price"] / 100) * ($row25["factor"] / 100)) - $row25["price"] / 100);
							}
						}
						
						
						$query7 = $db->query("SELECT * FROM `payment` WHERE `id_user` = '?i' AND `status` = '1' AND `date_pay` >= '".$dateStart."' AND `date_pay` <= '".$dateLast."'", $value11["id"]);
					
						unset($balanceWeek);
						
						while($row27 = $query7->fetch_assoc()) {
							$balanceWeek[] = $row27["price"];
						}
						
						if(!empty($balanceWeek)) {
							$balanceWeekNow = "0";
							for($i=0,$c = count($balanceWeek);$i < $c;++$i) {
								$balanceWeekNow += $balanceWeek[$i];
							}
						} else {
							$balanceWeekNow = "0";
						}
						
						if(!empty($loseUser)) {
							$loseUserBet = "0";
							for($i=0,$c = count($loseUser);$i < $c;++$i) {
								$loseUserBet += $loseUser[$i];
							}
						} else {
							$loseUserBet = "0";
						}
						
						if(!empty($winUser)) {
							$winUserBet = "0";
							for($i=0,$c = count($winUser);$i < $c;++$i) {
								$winUserBet += $winUser[$i];
							}
						} else {
							$winUserBet = "0";
						}
						
						if(!empty($TurneoverUser)) {
							$userTurneoverUser = "0";
							for($i=0,$c = count($TurneoverUser);$i < $c;++$i) {
								$userTurneoverUser += $TurneoverUser[$i];
							}
						} else {
							$userTurneoverUser = "0";
						}
						
						if(!empty($unresolvedUser)) {
							$userInGame = "0";
							for($i=0,$c = count($unresolvedUser);$i < $c;++$i) {
								$userInGame += $unresolvedUser[$i];
							}
						} else {
							$userInGame = "0";
						}
						
						$query278 = $db->query("SELECT * FROM `cash_out` WHERE `id_user` = '?i' AND `date` >= '".$dateStart."' AND `date` <= '".$dateLast."'", $value11["id"]);
						while($row278 = $query278->fetch_assoc()) {
							$cashout[] = $row278["price"];
						}
							
						if(!empty($cashout)) {
							$cashoutNow = "0";
							for($i=0,$c = count($cashout);$i < $c;++$i) {
								$cashoutNow += $cashout[$i];
							}
						} else {
							$cashoutNow = "0";
						}
						
						if($value11["balance"] == 0 AND $userInGame == 0 AND $balanceWeekNowU != $cashoutNow AND $balanceWeekNowU >= $cashoutNow) {
							if($value11["bonus_percent"] == 1) {
								$userBonusNow = $balanceWeekNow * "0.1";
							} elseif($value11["bonus_percent"] == 2) {
								$userBonusNow = $balanceWeekNow * "0.15";
							} elseif($value11["bonus_percent"] == 3) {
								$userBonusNow = $balanceWeekNow * "0.20";
							} else {
								$userBonusNow = $balanceWeekNow * "0.1";
							}
						} else {
							$userBonusNow = 0;
						}
						
						$userTurneoverUser = $userTurneoverUser + $userInGame;
						
						$allUserTotal = $winUserBet - $loseUserBet;
						
						$userNetto = $allUserTotal - $userBonusNow;
						
						if($user["verif"] == 1) {
							$AgentShareUser = $userNetto * "0.50";
							$CompanyShareUser = $userNetto * "0.50";
						} elseif($user["verif"] == 2) {
							$AgentShareUser = $userNetto * "0.60";
							$CompanyShareUser = $userNetto * "0.40";
						} elseif($user["verif"] == 3) {
							$AgentShareUser = $userNetto * "0.70";
							$CompanyShareUser = $userNetto * "0.30";
						} elseif($user["verif"] == 4) {
							$AgentShareUser = $userNetto * "0.80";
							$CompanyShareUser = $userNetto * "0.20";
						}
						
						if($allUserTotal > 0) {
							$allUserTotalSpan = '<span style="color: #36ef28;">'.$allUserTotal.'</span>';
						} else {
							$allUserTotalSpan = '<span style="color: #ff2a2a;">'.$allUserTotal.'</span>';
						}
						
						if($AgentShareUser > 0) {
							$AgentShareUserSpan = '<span style="color: #36ef28;">'.$AgentShareUser.'</span>';
						} else {
							$AgentShareUserSpan = '<span style="color: #ff2a2a;">'.$AgentShareUser.'</span>';
						}
						
						if($CompanyShareUser > 0) {
							$CompanyShareUserSpan = '<span style="color: #36ef28;">'.$CompanyShareUser.'</span>';
						} else {
							$CompanyShareUserSpan = '<span style="color: #ff2a2a;">'.$CompanyShareUser.'</span>';
						}
						
						if($userNetto > 0) {
							$userNettoSpan = '<span style="color: #36ef28;">'.$userNetto.'</span>';
						} else {
							$userNettoSpan = '<span style="color: #ff2a2a;">'.$userNetto.'</span>';
						}
						
						if($value11["status"] == 1) {
							$checkStatusUser = '<input type="checkbox" name="onoffswitch" value="'.$value11["id"].'">';
						} else {
							$checkStatusUser = '<input type="checkbox" checked="checked" name="onoffswitch" value="'.$value11["id"].'">';
						}
						
						$body .= '
								<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["login"].'
									</td>
									<td>
										'.$checkStatusUser.'
									</td>
									<td>
										'.$value11["balance"] / 100 . '<br/>
										<small>('. $balanceWeekNow .')</small>
									</td>
									<td>
										'.$userInGame.'
									</td>
									<td>
										<a href="/admin.php?do=listbet&filter='.$_GET["filter"].'&status=2&user_name='.$value11["login"].'">'.$openBetuser.'</a>
									</td>
									<td>
										<a href="/admin.php?do=listbet&filter='.$_GET["filter"].'&status=3&user_name='.$value11["login"].'">'.$closeBetuser.'</a>
									</td>
									<td>
										'.$userTurneoverUser.'
									</td>
									<td style="direction: ltr;">
										'.$allUserTotalSpan.'
									</td>
									
									<td>
										<a href="/admin.php?do=listmaster&option=edit&id='.$value11["id"].'">
											<div class="btn btn-primary right btn-primaryNew">Просмотр</div>
										</a>
									</td>
								</tr>
						';
						
						
						$riskBalance += $userInGame;
						$openBet += $openBetuser;
						$closeBet += $closeBetuser;
						$TurneoverAgent += $userTurneoverUser;
						$allUserBonus += $userBonusNow;
						$allUserNetto += $userNetto;
						$allAgentTotal += $allUserTotal;
						$AgentShareUserAll += $AgentShareUser;
						$CompanyShareUserAll += $CompanyShareUser;
					}
					
					
					

			if(empty($_POST)) {
				$pages = PageArray($all_page,$page,10,'/admin.php?do=listmaster');
				
				$tpl = new template('admin/template/master-view.tpl');
				$tpl->set('{body}', $body);
				$tpl->set('{page}', $pages);
				$content = $tpl->parse();
			}
			
	}
	
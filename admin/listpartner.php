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
		
		$query = $db->query('SELECT * FROM `users_kassa` WHERE `id` = "?i"', $_GET['id']);
		$row = $query->fetch_assoc();
		$row_check = $db->getAffectedRows();
		
		if($row_check == 1) {
			if(!empty($_POST)) {
				
				if(isset($_POST['button'])) {
					$query = $db->query('UPDATE `users_kassa` SET `login` = "?s", `limits` = "?i" WHERE `id` = "?i"', $_POST['login'], $_POST['limits'] * 100, $_GET["id"]);
					header('Location: /admin.php?do=listpartner&option=edit&id='.$_GET["id"]);
				}
				
				if(isset($_POST['button2'])) {
					$balance = $row['balance'] + $_POST['addbalance'] * 100;
					$query = $db->query('UPDATE `users_kassa` SET `balance` = "?i" WHERE `id` = "?i"', $balance, $_GET["id"]);
					header('Location: /admin.php?do=listpartner&option=edit&id='.$_GET["id"]);
				}
				
				if(isset($_POST['button3'])) {
					$balance = $row['balance'] - $_POST['removebalance'] * 100;
					$query = $db->query('UPDATE `users_kassa` SET `balance` = "?i" WHERE `id` = "?i"', $balance, $_GET["id"]);
					header('Location: /admin.php?do=listpartner&option=edit&id='.$_GET["id"]);
				}
				
				if(isset($_POST['button5'])) {
					$balance = 0;
					$query = $db->query('UPDATE `users_kassa` SET `balance` = "?i" WHERE `id` = "?i"', $balance, $_GET["id"]);
					header('Location: /admin.php?do=listpartner&option=edit&id='.$_GET["id"]);
				}
				
				if(isset($_POST['button6'])) {
					$balance = 0;
					$query = $db->query('UPDATE `users_kassa` SET `dohod` = "?i" WHERE `id` = "?i"', $balance, $_GET["id"]);
					header('Location: /admin.php?do=listpartner&option=edit&id='.$_GET["id"]);
				}
				
			} else {
					$query11 = $db->query('SELECT * FROM `cash_out` WHERE `system` = "?s" AND `status` = 1 ORDER BY `id` DESC LIMIT 100', $_GET['id']);
					$row11 = $query11->fetch_assoc_array();
					
					foreach($row11 as $key11 => $value11) {
						$cashout_table .= '
								<tr>
									<td>
										'.$value11["date"].'
									</td>
									<td>
										'.$value11["id_user"].'
									</td>
									<td>
										'.$value11["price"] / 100 .' TMT
									</td>
								</tr>
						';
					}
				
					$query11 = $db->query('SELECT * FROM `payment` WHERE `stream_id` = "?i" AND `status` = 1 ORDER BY `id` DESC LIMIT 100', $_GET['id']);
					$row11 = $query11->fetch_assoc_array();
					
					foreach($row11 as $key11 => $value11) {
						$payment_table .= '
								<tr>
									<td>
										'.$value11["date_pay"].'
									</td>
									<td>
										'.$value11["id_user"].'
									</td>
									<td>
										'.$value11["price"] .' TMT
									</td>
								</tr>
						';
					}
				
				
				
				
				$tpl = new template('admin/template/listpartner-edit.tpl');
				$tpl->set('{id}', $row["id"]);
				$tpl->set('{login}', $row["login"]);
				$tpl->set('{limits}', $row["limits"] / 100);
				$tpl->set('{balance}', $row["balance"] / 100);
				$tpl->set('{dohod}', $row["dohod"] / 100);
				$tpl->set('{payment_table}', $payment_table);
				$tpl->set('{cashout_table}', $cashout_table);
				$content = $tpl->parse();
			}
		}
		
	} elseif($_GET["option"] == "add") {
			if(!empty($_POST['login'])) {
				$password = md5(md5($_POST['password']));
				$db->query('INSERT INTO `users_kassa`(`login`,`password`) VALUES ("?s","?s")', $_POST['login'], $password);
				
				header('Location: /admin.php?do=listpartner');
			}
		
		
			$tpl = new template('admin/template/listpartner-addkassa.tpl');
			$tpl->set('{id}', $row["id"]);
			$content = $tpl->parse();
	} else {
		
				$query11 = $db->query("SELECT COUNT(id) FROM `users_kassa`");
				$row11 = $query11->fetch_assoc();
				$number_news = $row11["COUNT(id)"];
				$count_news = 100;
				$all_page = @ceil($number_news/$count_news);	/// Всего страниц
				if(isset($_GET['page'])) $page = intval($_GET['page']); /// Текущая страница
				if(empty($_GET['page'])) $page = 1; /// Проверка, где вы находитесь
				if($page == 0) $page = 1;
		
		
					if(!empty($_GET["login"])) {
						$query11 = $db->query('SELECT * FROM `users_kassa` WHERE `login` LIKE "%?s%" OR `id` LIKE "%?s%" ORDER BY `id` DESC', $_GET["login"], $_GET["login"]);
					} else {
						$query11 = $db->query('SELECT * FROM `users_kassa` ORDER BY `id` DESC LIMIT '.$count_news.' OFFSET '.(($page-1)*$count_news));
					}
					
					$row11 = $query11->fetch_assoc_array();
					foreach($row11 as $key11 => $value11) {
						
						$body .= '
								<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["login"].'
									</td>
									<td>
										'.$value11["balance"] / 100 .' TMT
									</td>
									<td>
										'.$value11["limits"] / 100 .' TMT
									</td>
									<td>
										'.$value11["dohod"] / 100 .' TMT
									</td>
									
									<td align="center">
										<a href="/admin.php?do=listpartner&option=edit&id='.$value11["id"].'">
											<div class="btn btn-primary right btn-primaryNew">Просмотр</div>
										</a>
									</td>
								</tr>
						';
						
					}
					
					
					

			if(empty($_POST)) {
				$pages = PageArray($all_page,$page,10,'/admin.php?do=listpartner');
				
				$tpl = new template('admin/template/listpartner-view.tpl');
				$tpl->set('{body}', $body);
				$tpl->set('{page}', $pages);
				$content = $tpl->parse();
			}
			
	}
	
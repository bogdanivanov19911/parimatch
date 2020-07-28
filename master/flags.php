<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	$date_events = date("Y-m-d H:i:s");
	
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
		
		$nav_selector = new template('master/template/page.tpl');
		$nav_selector->set('{prev}', "");
		$nav_selector->set('{page}', $page);
		$nav_selector->set('{next}', "");
		return $nav_selector->parse();
	}
	
	if(empty($_POST)) {
		$queryEvent = $db->query('SELECT `id`,`tournament_id` FROM `events` WHERE `is_live` = 0 AND `time_start` >= "'.$date_events.'"');
		$rowEvent = $queryEvent->fetch_assoc_array();
		
		$tournArray = array();
		
		foreach ($rowEvent as $key3 => $value3) {
			$tournArray[] = $value3["tournament_id"];
		}
		
		$tournStr = implode(",",$tournArray);
		

		$query11 = $db->query("SELECT COUNT(id) FROM `tournaments` WHERE `flags` IS NULL AND `id` IN ( ".$tournStr." ) ");
		$row11 = $query11->fetch_assoc();
		$number_news = $row11["COUNT(id)"];
		$count_news = 200;
		$all_page = @ceil($number_news/$count_news);	/// Всего страниц
		if(isset($_GET['page'])) $page = intval($_GET['page']); /// Текущая страница
		if(empty($_GET['page'])) $page = 1; /// Проверка, где вы находитесь
		if($page == 0) $page = 1;
		
		
		$query55 = $db->query('SELECT * FROM `tournaments` WHERE `flags` IS NULL AND `id` IN ( '.$tournStr.' ) LIMIT '.$count_news.' OFFSET '.(($page-1)*$count_news));
		$row55 = $query55->fetch_assoc_array();
		
		foreach($row55 as $key => $value) {
			$row = $query->fetch_assoc();
			$body .= '
					<tr>
						<td>
							'.$value["name"].'
						</td>
						<td>
							<input type="text" name="'.$value["id"].'">
						</td>
					</tr>
			';
		}
		
		
		$navigation = PageArray($all_page,$page,10,'/master.php?do=flags');
		$pages = $navigation;
		
		$tpl = new template('master/template/flags.tpl');
		$tpl->set('{body}', $body);
		$tpl->set('{page}', $pages);
		$content = $tpl->parse();
	} else {
		foreach($_POST as $key => $value) {
			if($key != "button") {
				if(!empty($value)) {
					$value = str_replace("http://saturn-partner.com","",$value);
					
					$db->query('UPDATE `tournaments` SET `flags` = "?s" WHERE `id` = "?i"', $value,$key);
				}
			}
		}
		header('Location: /master.php?do=flags');
	}
?>
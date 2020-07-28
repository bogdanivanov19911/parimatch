<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	$date_events = date("Y-m-d H:i:s");
	
	if(empty($_POST) AND empty($_GET["id"])) {
		
		$month_names = array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"); 
		if (isset($_GET['y'])) $y=$_GET['y'];
		if (isset($_GET['m'])) $m=$_GET['m']; 
		if (isset($_GET['date']) AND strstr($_GET['date'],"-")) list($y,$m)=explode("-",$_GET['date']);
		if (!isset($y) OR $y < 1970 OR $y > 2037) $y=date("Y");
		if (!isset($m) OR $m < 1 OR $m > 12) $m=date("m");

		$month_stamp=mktime(0,0,0,$m,1,$y);
		$day_count=date("t",$month_stamp);
		$weekday=date("w",$month_stamp);
		if ($weekday==0) $weekday=7;
		$start=-($weekday-2);
		$last=($day_count+$weekday-1) % 7;
		if ($last==0) $end=$day_count; else $end=$day_count+7-$last;
		$today=date("Y-m-d");
		$prev= '/master.php?do=analitic'. date('&\m=m&\y=Y',mktime (0,0,0,$m-1,1,$y));  
		$next= '/master.php?do=analitic'. date('&\m=m&\y=Y',mktime (0,0,0,$m+1,1,$y));
		$i=0;
	  
		$days .= '<table border=1 cellspacing=0 cellpadding=2 class="calendar"> 
					 <tr>
					  <td colspan=7> 
					   <table width="100%" border=0 cellspacing=0 cellpadding=0> 
						<tr> 
						 <td align="left"><a href="'. $prev .'">&lt;&lt;&lt;</a></td> 
						 <td align="center">'. $month_names[$m-1] . ' ' . $y .'</td> 
						 <td align="right"><a href="'. $next .'">&gt;&gt;&gt;</a></td> 
						</tr> 
					   </table> 
					  </td> 
					 </tr> 
					 <tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td><tr>';
			
			
		for($d=$start;$d<=$end;$d++) {
			if (!($i++ % 7))  $days .=  " <tr>\n";
			$days .=  '  <td align="center">';
			if ($d < 1 OR $d > $day_count) {
				$days .=  "&nbsp";
			} else {
				$now="$y-$m-".sprintf("%02d",$d);
				$days .=  '<b><a href="/master.php?do=analitic&date='.$now.'">'.$d.'</a></b>'; 
			}
			$days .=  "</td>\n";
			if (!($i % 7))   $days .=  " </tr>\n";
		} 
		
		$days .= '</table>';
			
			
		if(!empty($_GET["date"])) {
			$dateStart = $_GET["date"]." 00:00:01";
			$dateLast = $_GET["date"]." 23:59:59";
		} else {
			$dateStart = date("Y-m-d 00:00:01");
			$dateLast = date("Y-m-d 23:59:59");
		}
		
		
		$query11 = $db->query('SELECT * FROM `users` WHERE `register_data` >= "'.$dateStart.'" AND `register_data` <= "'.$dateLast.'" ORDER BY `id` DESC ');
		$row11 = $query11->fetch_assoc_array();
		
		foreach($row11 as $key11 => $value11) {
			$body .= '			<tr>
									<td>
										'.$value11["id"].'
									</td>
									<td>
										'.$value11["login"].'
									</td>
									<td>
										'.$value11["register_data"].'
									</td>
									<td>
										<a href="/master.php?do=analitic&option=view&id='.$value11["id"].'">
											<div class="btn btn-primary btn-primaryNew">Просмотр</div>
										</a>
									</td>
								</tr>';
		}
		
		
		
		
		$tpl = new template('master/template/analitic.tpl');
		
		if(!empty($_GET["id"])) {
			$tpl->set('{days}', "");
		} else {
			$tpl->set('{days}', '<div style="display: block; height: 240px;">'.$days."</div>");
		}
		$tpl->set('{body}', $body);
		
		$content = $tpl->parse();
	} else {
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
		} else {
			$dateStart = date("Y-m-d 00:00:01");
			$dateLast = date("Y-m-d 23:59:59");
		}
		
		$queryD = $db->query("SELECT * FROM `payment` WHERE `id_user` = '?i' AND (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$dateStart."' AND `date_pay` <= '".$dateLast."'", $_GET["id"]);
		$rowD = $queryD->fetch_assoc_array();
		
		$d=0;
		$summAll = 0;
		foreach($rowD as $keyD => $valueD) {
			$summAll += $valueD["price"];
			$body .= "
						<tr>
							<td>
								".$valueD["id_user"]."
							</td>
							<td>
								".$valueD["price"]."
							</td>
							<td>
								".$valueD["date_pay"]."
							</td>
						</tr>
			";
			$d++;
		}
		
				$body .= "
						<tr>
							<td>Общее:</td>
							<td>
								".$summAll."
							</td>
							<td>Количество: ".$d."</td>
						</tr>
			";
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `id_user` = '?i' AND `status` = 1 AND `date` >= '".$dateStart."' AND `date` <= '".$dateLast."'", $_GET["id"]);
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		$summAllW = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$body2 .= "
						<tr>
							<td>
								".$valueW["id_user"]."
							</td>
							<td>
								".$valueW["price"] / 100 ."
							</td>
							<td>
								".$valueW["date"]."
							</td>
						</tr>
			";
			$w++;
		}
		
		$body2 .= "
						<tr>
							<td>Общее: </td>
							<td>
								".$summAllW."
							</td>
							<td>Количество: ".$w."</td>
						</tr>
			";
		
		$query11 = $db->query('SELECT * FROM `users` WHERE `id` = "?i"',$_GET["id"]);
		$row11 = $query11->fetch_assoc();
		
		
		
		$tpl = new template('master/template/analitic-view.tpl');
		$tpl->set('{depositsCount}', $d);
		$tpl->set('{depositsSumm}', $summAll);
		$tpl->set('{cashoutCount}', $w);
		$tpl->set('{cashoutSumm}', $summAllW);
		$tpl->set('{days}', "");
		$tpl->set('{body}', $body);
		$tpl->set('{body2}', $body2);
		$tpl->set('{balance_user}', $row11["balance"] / 100);
		$tpl->set('{userbets}', "/master.php?do=listbet&filter=1&status=1&user_name=".$row11["login"]);
		$tpl->set('{useredit}', "/master.php?do=listmaster&option=edit&id=".$_GET["id"]);
		
		$content = $tpl->parse();
		
		
		
	}
?>
<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	date_default_timezone_set("UTC");
	$date_events = date("Y-m-d H:i:s");
	
	if(empty($_POST)) {
		
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
		$prev= '/master.php?do=statistic'. date('&\m=m&\y=Y',mktime (0,0,0,$m-1,1,$y));  
		$next= '/master.php?do=statistic'. date('&\m=m&\y=Y',mktime (0,0,0,$m+1,1,$y));
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
				$days .=  '<b><a href="/master.php?do=statistic&date='.$now.'">'.$d.'</a></b>'; 
			}
			$days .=  "</td>\n";
			if (!($i % 7))   $days .=  " </tr>\n";
		} 
		
		$days .= '</table>';
		
		
		$queryAllUser = $db->query("SELECT COUNT(id) FROM `users`");
		$rowAllUser = $queryAllUser->fetch_assoc();
		$numberAllUsers = $rowAllUser["COUNT(id)"];
		
		$queryAllDeposits = $db->query("SELECT `price` FROM `payment` WHERE `status` = 1 AND `hide` IS NULL");
		$rowAllDeposits = $queryAllDeposits->fetch_assoc_array();
		
		$depositsCount = 0;
		foreach($rowAllDeposits as $key => $value) {
			$depositsSumm += $value["price"];
			$depositsCount++;
		}
		
		$queryAllCashOut = $db->query("SELECT `price` FROM `cash_out` WHERE `status` = 1");
		$rowAllCashOut = $queryAllCashOut->fetch_assoc_array();
		
		$CashOutCount = 0;
		foreach($rowAllCashOut as $key2 => $value2) {
			$CashOutSumm += $value2["price"] / 100;
			$CashOutCount++;
		}
		

		$queryAllDeposits2 = $db->query("SELECT `price`,`id_user` FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL GROUP BY `id_user`");
		$rowAllDeposits2 = $queryAllDeposits2->fetch_assoc_array();
		
		$depositsCount2 = 0;
		foreach($rowAllDeposits2 as $key3 => $value3) {
			$depositsSumm2 += $value["price"];
			$depositsCount2++;
		}

        if(!empty($_GET["date"])) {
            $dateStart = $_GET["date"]." 00:00:01";
            $dateLast = $_GET["date"]." 23:59:59";

            $queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$dateStart."' AND `date_pay` <= '".$dateLast."'");
        } else {
            $dateStart = date("Y-m-d 00:00:01");
            $dateLast = date("Y-m-d 23:59:59");

            $queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$dateStart."' AND `date_pay` <= '".$dateLast."'");
        }

        //case stats

		$created_from = strtotime($dateStart);
		$created_to = strtotime($dateLast);

		$tpl = new template('master/template/statistic.tpl');
		$tpl->set('{days}', $days);
		$tpl->set('{numberAllUsers}', $numberAllUsers);
		$tpl->set('{numberAllDeposits}', $depositsCount);
		$tpl->set('{numberAllDepositsSumm}', $depositsSumm);
		$tpl->set('{numberAllCashOut}', $CashOutCount);
		$tpl->set('{numberAllCashOutSumm}', $CashOutSumm);
		$tpl->set('{unikDeposit}', $depositsCount - $depositsCount2);



        $rowD = $queryD->fetch_assoc_array();
		
		$d=0;
		$depositOld=0;
		$depositUnikal=0;
		foreach($rowD as $keyD => $valueD) {
			$queryDUN = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `id_user` = '?i' LIMIT 2", $valueD["id_user"]);
			$rowDUN = $queryDUN->fetch_assoc();
			
			if($db->getAffectedRows() >= 2) {
				$depositOld++;
			} else {
				$depositUnikal++;
			}
			
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
			$summAll += $valueD["price"];
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
			
			
		$queryAllDeposits3 = $db->query("SELECT `price`,`id_user` FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$dateStart."' AND `date_pay` <= '".$dateLast."' GROUP BY `id_user`");
		$rowAllDeposits3 = $queryAllDeposits3->fetch_assoc_array();
		
		$depositsCount3 = 0;
		foreach($rowAllDeposits3 as $key4 => $value4) {
			$depositsSumm3 += $value4["price"];
			$depositsCount3++;
		}
			
		$tpl->set('{depositsCountToday}', $d);
		$tpl->set('{depositsSummToday}', $summAll);
		$tpl->set('{unikDepositToday}', $depositOld);
		$tpl->set('{newDepositToday}', $depositUnikal);
			
			
			
		$queryUsersCount = $db->query("SELECT `id` FROM `users` WHERE `register_data` >= '".$dateStart."' AND `register_data` <= '".$dateLast."'");
		$rowUsers = $queryUsersCount->fetch_assoc_array();
		
		$usersCount = 0;
		foreach($rowUsers as $key5 => $value5) {
			$usersCount++;
		}
		$tpl->set('{newUsersCount}', $usersCount);
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		if(!empty($_GET["date"])) {
			$dateStart = $_GET["date"]." 00:00:01";
			$dateLast = $_GET["date"]." 23:59:59";
			
			$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$dateStart."' AND `date` <= '".$dateLast."'");
		} else {
			$dateStart = date("Y-m-d 00:00:01");
			$dateLast = date("Y-m-d 23:59:59");
			
			$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$dateStart."' AND `date` <= '".$dateLast."'");
		}
		
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
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
			$summAllW += $valueW["price"] / 100;
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
			
			
			
			
			
			
			
			
			
			
			
			
		
		
		
		$tpl->set('{body}', $body);
		$tpl->set('{body2}', $body2);
		$tpl->set('{page}', $pages);
		$content = $tpl->parse();
	} else {
		
	}
?>
<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	$tpl = new template('master/template/body.tpl');
	$arr = array(
		  '0',
		  'янв',
		  'фев',
		  'мар',
		  'апр',
		  'май',
		  'июн',
		  'июл',
		  'авг',
		  'сен',
		  'окт',
		  'ноя',
		  'дек'
	);
	
	$last7days = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
	$end7days = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
	
	
	$period = new DatePeriod(
		new DateTime($last7days),
		new DateInterval('P1D'),
		new DateTime($end7days)
	);
	
	$dates = array();
	
	foreach ($period as $key => $value) {
		$dates[$value->format('Y-m-d')] = array(
			"mon" => $arr[$value->format('m')],
			"day" => $value->format('d'),
		);
	}
	
	
	foreach($dates as $key2 => $value2) {
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$key2." 00:00:01' AND `date_pay` <= '".$key2." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$key2." 00:00:01' AND `date` <= '".$key2." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		$dates[$key2]["widt"] = $summAllW;
		$dates[$key2]["deposit"] = $depAll;
	}
	
	foreach($dates as $key3 => $value3) {
		$labels .= '"'.$value3["day"].' '.$value3["mon"].'",';
		$dateW .= "'".$value3["widt"]."',";
		$dateD .= "'".$value3["deposit"]."',";
	}
	
	

	$startToday = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$endToday = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startToday." 00:00:01' AND `date_pay` <= '".$endToday." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE (`status` = 1 OR `status` = 5) AND `date` >= '".$startToday." 00:00:01' AND `date` <= '".$endToday." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{depToday}', $depAll);
	$tpl->set('{widtToday}', $summAllW);
	$tpl->set('{dohodToday}', $depAll - $summAllW);
	
	$startDate = date("Y-m-d" ,strtotime("last week"));
	$endDate = date("Y-m-d" ,strtotime("this week"));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startDate." 00:00:01' AND `date_pay` <= '".$endDate." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$startDate." 00:00:01' AND `date` <= '".$endDate." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{deplastweek}', $depAll);
	$tpl->set('{widtlastweek}', $summAllW);
	$tpl->set('{dohodlastweek}', $depAll - $summAllW);
	
	
	$startDate = date("Y-m-d" ,strtotime("this week")); // Текущая неделя
	$endDate = date("Y-m-d" ,strtotime("now"));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startDate." 00:00:01' AND `date_pay` <= '".$endDate." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE (`status` = 1 OR `status` = 5) AND `date` >= '".$startDate." 00:00:01' AND `date` <= '".$endDate." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{depthisweek}', $depAll);
	$tpl->set('{widtthisweek}', $summAllW);
	$tpl->set('{dohodthisweek}', $depAll - $summAllW);
	
	$startDate = date("Y-m-d" ,strtotime("last day")); // Вчера
	$endDate = date("Y-m-d" ,strtotime("last day"));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startDate." 00:00:01' AND `date_pay` <= '".$endDate." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$startDate." 00:00:01' AND `date` <= '".$endDate." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{deplastday}', $depAll);
	$tpl->set('{widtlastday}', $summAllW);
	$tpl->set('{dohodlastday}', $depAll - $summAllW);
	
	$startDate = date("Y-m-d" ,strtotime("first day of previous month")); // Прошлый месяц
	$endDate = date("Y-m-d" ,strtotime("last day of previous month"));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startDate." 00:00:01' AND `date_pay` <= '".$endDate." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$startDate." 00:00:01' AND `date` <= '".$endDate." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{deplastmonth}', $depAll);
	$tpl->set('{widtlastmonth}', $summAllW);
	$tpl->set('{dohodlastmonth}', $depAll - $summAllW);
	
	$startDate = date("Y-m-d" ,strtotime("first day of this month")); // Текущий месяц
	$endDate = date("Y-m-d" ,strtotime("last day of this month"));
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startDate." 00:00:01' AND `date_pay` <= '".$endDate." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$startDate." 00:00:01' AND `date` <= '".$endDate." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
		
		if($depAll <= 0) {
			$depAll = 0;
		}
		
		if($summAllW <= 0) {
			$summAllW = 0;
		}
	
	$tpl->set('{depthismonth}', $depAll);
	$tpl->set('{widtthismonth}', $summAllW);
	$tpl->set('{dohodthismonth}', $depAll - $summAllW);
	
	$startMonth = date("Y-m-d" ,mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
	$endMonth = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	
	
		$queryD = $db->query("SELECT * FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startMonth." 00:00:01' AND `date_pay` <= '".$endMonth." 23:59:59'");
        $rowD = $queryD->fetch_assoc_array();
		
		unset($depAll,$summAllW);
		
		$d=0;
		foreach($rowD as $keyD => $valueD) {
			$depAll += $valueD["price"];
			$d++;
		}
		
		$queryW = $db->query("SELECT * FROM `cash_out` WHERE `status` = 1 AND `date` >= '".$startMonth." 00:00:01' AND `date` <= '".$endMonth." 23:59:59'");
		$rowW = $queryW->fetch_assoc_array();
		
		$w = 0;
		foreach($rowW as $keyW => $valueW) {
			$summAllW += $valueW["price"] / 100;
			$w++;
		}
	
	$tpl->set('{depMonth}', $depAll);
	$tpl->set('{widtMonth}', $summAllW);
	$tpl->set('{dohodMonth}', $summAllW);
	$tpl->set('{labels}', $labels);
	$tpl->set('{dateW}', $dateW);
	$tpl->set('{dateD}', $dateD);
	$tpl->set('{balance}', $user['balance_agent'] / 100);
	$content = $tpl->parse();
?>
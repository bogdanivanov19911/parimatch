<?php
	define('bk', true);
	header('Content-Type: text/html; charset=utf-8');
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);
	define('MAX_FILE_SIZE', 9999999999);
	set_time_limit(58);
	
	$start = microtime(true);
	
	for ($i = 1; $i <= 45; $i++) {
		$data = file_get_contents("http://185.43.223.70/engine/cron/api/data/live.json");
		
		file_put_contents(__DIR__ ."/data/live.json", $data, LOCK_EX);
		
		if((microtime(true) - $start) >= 58) {
			exit($i);
		}
		sleep(2);
	}
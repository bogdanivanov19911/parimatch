<?php

	$db = Database_Mysql::create("localhost", "pm", "Ed503f2123")
	->setDatabaseName("pm")
	->setCharset("utf8") or die("Ошибка!");
	
	
	
	define('MAILHOST', 'sendmail.gyzylburgut2.com');
	define('MAILUSER', 'info@sendmail.gyzylburgut2.com');
	define('MAILPASS', 'A9zecw6h123');
	define('SITENAME', 'Parimatch');


	define('MERCHANTID', '173628');
	define('MERCHANTSECRET', '41122h5l');
	define('MERCHANTSECRET2', '41122h5l');
	
?>
	
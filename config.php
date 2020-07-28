<?php

	$db = Database_Mysql::create("localhost", "pm", "L7v4P8h8")
	->setDatabaseName("parimatch")
	->setCharset("utf8") or die("Ошибка!");
	

	define('MAILHOST', 'sendmail.gyzylburgut2.com');
	define('MAILUSER', 'info@sendmail.gyzylburgut2.com');
	define('MAILPASS', 'A9zecw6h123');
	define('SITENAME', 'Parimatch');

    define('MERCHANTID', '218454');
    define('MERCHANTSECRET', 'eq3pqtks');
    define('MERCHANTSECRET2', 'eq3pqtks');
	
?>
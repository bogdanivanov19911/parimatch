<?php
define('bk', true);
header('Content-Type: text/html; charset=utf-8');
@ini_set('display_errors', false);
@ini_set('html_errors', false);
define('MAX_FILE_SIZE', 99999999999);
set_time_limit(0);

require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/mysqli.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

$date = date("Y-m-d");
$date = strtotime($date);
$date = strtotime("-30 day", $date);
$date = date('Y-m-d H:i:s', $date);

$query = $db->query("SELECT `id` FROM `events` WHERE `result` IS NOT NULL AND `time_start` < '?s'", $date);
$row = $query->fetch_assoc_array();

foreach ($row as $key => $value) {
    $db->query("DELETE FROM `events` WHERE `id` = '?i'", $value["id"]);
}


?>
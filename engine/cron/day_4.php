<?php
define('bk', true);
@ini_set('display_errors', false);
require_once(__DIR__ . '/../classes/mysqli.php');
require_once("./../../config.php");

$curr_time = (new \DateTime('now'));//->add(new \DateInterval("PT60M"));

$id = '89734';

$queryEvent = $db->query('SELECT time_start, need_score FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
$result = $queryEvent->fetch_assoc();
$time_start_for_1_match = $result['time_start'];
$needScore = json_decode($result['need_score']);

$time_start = new \DateTime($time_start_for_1_match);
$time_start->setTime('18', '00');

$time_end = ((new DateTime($time_start_for_1_match))->setTime('18', '00'))->add(new  DateInterval('PT109M'));

//var_dump($curr_time);die;
//var_dump($time_start);
//var_dump($time_end);


if (($curr_time > $time_start) && !($curr_time > $time_end)) {
    $diff = $time_start->diff($curr_time);
    $minutes = $diff->days * 24 * 60;
    $minutes += $diff->h * 60;
    $minutes += $diff->i;
    if (in_array($minutes, [46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61])) {
        $timeout = true;
    }
    if ($minutes >= 61) {
        $minutes -= 15;
        $timeout = false;
    }

    foreach ($needScore as $min => $score) {
        if ($min == $minutes) {
            $queryEvent = $db->query('SELECT `bets` FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
            $bets = $queryEvent->fetch_assoc()['bets'];
            if ($score == '1:0') {
                $evt = '{"1X2":{"1":{"kf":"1.34"},"2":{"kf":"3.29"},"3":{"kf":"3.75"}},"WXM":{"1":{"kf":"1.12"},"2":{"kf":"1.09"},"3":{"kf":"1.58"}},"T":{"1":{"1":{"kf":"1.74","lv":"2.5"},"2":{"kf":"2.1","lv":"2.5"}}, "2":{"1":{"kf":"1.25","lv":"3.5"},"2":{"kf":"4.1","lv":"3.5"}}},"2TIMEHAVEGOALS":{"1":{"kf":"1.27"},"2":{"kf":"3.55"}},"OZIN12TYME":{"1":{"kf":"1.56"},"2":{"kf":"1.87"}},"TOTALYNCHET":{"1":{"kf":"1.92"},"2":{"kf":"1.83"}},"SCR0":{"1":{"kf":"8.2"},"2":{"kf":"14"},"3":{"kf":"29"},"4":{"kf":"70"},"5":{"kf":"10.5"},"6":{"kf":"22"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"11":{"kf":"6.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"130"}},"INDTOTAL1":{"5":{"1":{"kf":"1.63","lv":"1.5"},"2":{"kf":"2.2","lv":"1.5"}}},"INDTOTAL2":{"5":{"1":{"kf":"1.93","lv":"1.5"},"2":{"kf":"1.81","lv":"1.5"}}},"F":{}}';
            }
            if ($score == '1:1') {
                $evt = '{"1X2":{"1":{"kf":"1.89"},"2":{"kf":"2.62"},"3":{"kf":"1.8"}},"WXM":{"1":{"kf":"1.69"},"2":{"kf":"1.27"},"3":{"kf":"1.38"}},"T":{"1":{"1":{"kf":"1.84","lv":"2.5"},"2":{"kf":"1.91","lv":"2.5"}},"3":{"1":{"kf":"1.23","lv":"3.5"},"2":{"kf":"3.9","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.78"},"2":{"kf":"1.97"}},"SCR0":{"5":{"kf":"10.5"},"6":{"kf":"14"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"11":{"kf":"4.23"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"130"}},"F":{}}';
            }
            if ($score == '2:1') {
                $evt = '{"1X2":{"1":{"kf":"1.43"},"2":{"kf":"2.99"},"3":{"kf":"3.21"}},"WXM":{"1":{"kf":"1.35"},"2":{"kf":"1.17"},"3":{"kf":"1.58"}},"T":{"1":{"1":{"kf":"1.24","lv":"3.5"},"2":{"kf":"2.17","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.76"},"2":{"kf":"1.88"}},"SCR0":{"5":{"kf":"10.5"},"6":{"kf":"8"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"23":{"kf":"87"},"24":{"kf":"141"}},"F":{}}';
            }
            if ($score == '3:1') {
                $evt = '{"1X2":{"1":{"kf":"1.14"},"2":{"kf":"3.31"},"3":{"kf":"4.81"}},"WXM":{"1":{"kf":"1.29"},"2":{"kf":"1.14"},"3":{"kf":"1.63"}},"T":{},"TOTALYNCHET":{"1":{"kf":"1.45"},"2":{"kf":"2.12"}},"SCR0":{"6":{"kf":"1.81"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"13":{"kf":"65"},"14":{"kf":"250"},"24":{"kf":"130"}},"F":{}}';
            }
            if (!$timeout) {
                $db->query('UPDATE `events` SET `is_live` = "?s", `bets` = "?s", `min` = "?s", `curr_time` = "?s", score = "?s" WHERE `events`.`id` = "?s";', 1, $evt, $minutes, $curr_time->format('Y-m-d H:i:s'), $score, $id);
            }
        }
    }
    if ($timeout) {
        $db->query('UPDATE `events` SET `is_live` = "?s", `min` = "?s", `curr_time` = "?s" WHERE `events`.`id` = "?s";', 1, 'пер', $curr_time->format('Y-m-d H:i:s'), $id);
    } else {
        $db->query('UPDATE `events` SET `is_live` = "?s", `min` = "?s", `curr_time` = "?s" WHERE `events`.`id` = "?s";', 1, $minutes, $curr_time->format('Y-m-d H:i:s'), $id);
    }
}

if (($curr_time > $time_end) && ($curr_time->diff($time_end)->i < 2)) {
    $db->query('UPDATE `events` SET `is_live` = "?s", `result` = TRUE WHERE `events`.`id` = "?s";', 0, $id);
}





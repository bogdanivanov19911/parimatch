<?php
define('bk', true);
@ini_set('display_errors', false);
require_once(__DIR__ . '/../classes/mysqli.php');
require_once("./../../config.php");

$curr_time = (new \DateTime('now'));//->add(new \DateInterval("PT60M"));

$id = 89735;

$queryEvent = $db->query('SELECT time_start, need_score FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
$result = $queryEvent->fetch_assoc();
$time_start_for_1_match = $result['time_start'];
$needScore = json_decode($result['need_score']);

$time_start = new \DateTime($time_start_for_1_match);
$time_start->setTime('18', '00');

$time_end = ((new DateTime($time_start_for_1_match))->setTime('18', '00'))->add(new  DateInterval('PT111M'));

//var_dump($curr_time);die;
//var_dump($time_start);
//var_dump($time_end);


if (($curr_time > $time_start) && !($curr_time > $time_end)) {
    $diff = $time_start->diff($curr_time);
    $minutes = $diff->days * 24 * 60;
    $minutes += $diff->h * 60;
    $minutes += $diff->i;
    if (in_array($minutes, [47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62])) {
        $timeout = true;
    }
    if($minutes >= 62) {
        $minutes -= 15;
        $timeout = false;
    }

    foreach ($needScore as $min => $score) {
        if($min == $minutes) {
            $queryEvent = $db->query('SELECT `bets` FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
            $bets = $queryEvent->fetch_assoc()['bets'];
            if ($score == '1:0') {
                $evt = '{"1X2":{"1":{"kf":"2.34"},"2":{"kf":"2.89"},"3":{"kf":"3.75"}},"WXM":{"1":{"kf":"1.35"},"2":{"kf":"1.17"},"3":{"kf":"1.48"}},"T":{"0":{"1":{"kf":"1.23","lv":"2.0"},"2":{"kf":"3.9","lv":"2.0"}},"1":{"1":{"kf":"1.74","lv":"2.5"},"2":{"kf":"2.1","lv":"2.5"}},"6":{"1":{"kf":"2.31","lv":"3.5"},"2":{"kf":"1.55","lv":"3.5"}}},"2TIMEHAVEGOALS":{"1":{"kf":"1.27"},"2":{"kf":"3.55"}},"OZIN12TYME":{"1":{"kf":"1.87"},"2":{"kf":"1.87"}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"1":{"kf":"8.2"},"2":{"kf":"14"},"3":{"kf":"29"},"4":{"kf":"70"},"5":{"kf":"10.5"},"6":{"kf":"24"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"11":{"kf":"6.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"19":{"kf":"9.8"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"61"}},"INDTOTAL1":{"5":{"1":{"kf":"1.63","lv":"1.5"},"2":{"kf":"2.2","lv":"1.5"}}},"INDTOTAL2":{"5":{"1":{"kf":"1.93","lv":"1.5"},"2":{"kf":"1.81","lv":"1.5"}}},"F":{}}';
            }
            if ($score == '1:1') {
                $evt = '{"1X2":{"1":{"kf":"2.55"},"2":{"kf":"2.62"},"3":{"kf":"1.8"}},"WXM":{"1":{"kf":"1.69"},"2":{"kf":"1.27"},"3":{"kf":"1.38"}},"T":{"1":{"1":{"kf":"1.84","lv":"2.5"},"2":{"kf":"2.1","lv":"2.5"}},"6":{"1":{"kf":"2.26","lv":"3.5"},"2":{"kf":"1.51","lv":"3.5"}}},"2TIMEHAVEGOALS":{"1":{"kf":"1.27"},"2":{"kf":"3.55"}},"OZIN12TYME":{"1":{"kf":"1.87"},"2":{"kf":"1.87"}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"5":{"kf":"10.5"},"6":{"kf":"24"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"11":{"kf":"4.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"19":{"kf":"9.8"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"48"}},"F":{}}';
            }
            if ($score == '2:1') {
                $evt = '{"1X2":{"1":{"kf":"2.03"},"2":{"kf":"2.99"},"3":{"kf":"3.21"}},"WXM":{"1":{"kf":"1.35"},"2":{"kf":"1.17"},"3":{"kf":"1.58"}},"T":{"6":{"1":{"kf":"1.41","lv":"3.5"},"2":{"kf":"1.58","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"5":{"kf":"8.5"},"6":{"kf":"24"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"12":{"kf":"10.2"},"13":{"kf":"65"},"14":{"kf":"250"},"23":{"kf":"75"},"24":{"kf":"37"}},"F":{}}';
            }
            if ($score == '3:1') {
                    $evt = '{"1X2":{"1":{"kf":"1.67"},"2":{"kf":"3.31"},"3":{"kf":"3.81"}},"WXM":{"1":{"kf":"1.29"},"2":{"kf":"1.14"},"3":{"kf":"1.63"}},"T":{},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"6":{"kf":"12"},"7":{"kf":"34"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"140"},"13":{"kf":"65"},"14":{"kf":"250"},"24":{"kf":"21"}},"F":{}}';
            }
            if ($score == '3:2') {
                $evt = '{"1X2":{"1":{"kf":"2.08"},"2":{"kf":"2.21"},"3":{"kf":"3.71"}},"WXM":{"1":{"kf":"1.34"},"2":{"kf":"1.57"},"3":{"kf":"1.48"}},"T":{},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"7":{"kf":"34"},"9":{"kf":"85"},"10":{"kf":"140"},"13":{"kf":"65"},"14":{"kf":"250"},"24":{"kf":"13"}},"F":{}}';
            }
            if ($score == '3:3') {
                $evt = '{"1X2":{"1":{"kf":"2.94"},"2":{"kf":"2.01"},"3":{"kf":"1.63"}},"WXM":{"1":{"kf":"1.69"},"2":{"kf":"1.87"},"3":{"kf":"1.38"}},"T":{},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"13":{"kf":"8"},"14":{"kf":"28"},"24":{"kf":"9"}},"F":{}}';
            }
            if ($score == '3:4') {
                $evt = '{"1X2":{"1":{"kf":"4.21"},"2":{"kf":"1.21"},"3":{"kf":"2.51"}},"WXM":{"1":{"kf":"1.69"},"2":{"kf":"1.27"},"3":{"kf":"1.28"}},"T":{},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"14":{"kf":"23"},"24":{"kf":"1.89"}},"F":{}}';
            }
            if(!$timeout) {
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





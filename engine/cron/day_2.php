<?php
define('bk', true);
@ini_set('display_errors', false);
require_once(__DIR__ . '/../classes/mysqli.php');
require_once("./../../config.php");

$curr_time = (new \DateTime('now'));//->add(new \DateInterval("PT60M"));

$id = 89732;

$queryEvent = $db->query('SELECT time_start, need_score FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
$result = $queryEvent->fetch_assoc();
$time_start_for_1_match = $result['time_start'];
$needScore = json_decode($result['need_score']);

$time_start = new \DateTime($time_start_for_1_match);
$time_start->setTime('18', '00');

$time_end = ((new DateTime($time_start_for_1_match))->setTime('18', '00'))->add(new  DateInterval('PT110M'));

//var_dump($curr_time);die;
//var_dump($time_start);
//var_dump($time_end);


if (($curr_time > $time_start) && !($curr_time > $time_end)) {
    $diff = $time_start->diff($curr_time);
    $minutes = $diff->days * 24 * 60;
    $minutes += $diff->h * 60;
    $minutes += $diff->i;
    if (in_array($minutes, [47, 48, 49, 50, 51, 52, 53, 54, 55 ,56, 57, 58, 59, 60, 61, 62, 63])) {
        $timeout = true;
    }
    if($minutes >= 63) {
        $minutes -= 15;
        $timeout = false;
    }

    foreach ($needScore as $min => $score) {
        if($min == $minutes) {
            $queryEvent = $db->query('SELECT `bets` FROM `events` WHERE `id` = "?s" LIMIT 1', $id);
            $bets = $queryEvent->fetch_assoc()['bets'];
            if ($score == '0:1') {
                $evt = '{"1X2":{"1":{"kf":"3.21"},"2":{"kf":"1.84"},"3":{"kf":"2.79"}},"WXM":{"1":{"kf":"1.82"},"2":{"kf":"1.24"},"3":{"kf":"1.24"}},"T":{"0":{"1":{"kf":"1.23","lv":"2.0"},"2":{"kf":"1.91","lv":"2.0"}},"1":{"1":{"kf":"1.18","lv":"2.5"},"2":{"kf":"2.1","lv":"2.5"}},"6":{"1":{"kf":"1.37","lv":"3.5"},"2":{"kf":"1.85","lv":"3.5"}}}, "2TIMEHAVEGOALS":{"1":{"kf":"1.27"},"2":{"kf":"3.55"}},"OZIN12TYME":{"1":{"kf":"1.54"},"2":{"kf":"1.87"}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"5":{"kf":"10.5"},"6":{"kf":"22"},"7":{"kf":"39"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"123"},"11":{"kf":"6.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"15":{"kf":"1.12"},"16":{"kf":"9.11"},"17":{"kf":"18"},"18":{"kf":"60"},"19":{"kf":"16"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"128"}},"INDTOTAL1":{"5":{"1":{"kf":"1.71","lv":"1.5"},"2":{"kf":"1.80","lv":"1.5"}}},"INDTOTAL2":{"5":{"1":{"kf":"1.89","lv":"1.5"},"2":{"kf":"1.71","lv":"1.5"}}},"F":{"0":{"1":{"kf":"5.5","lv":"-1.5"},"2":{"kf":"3.7","lv":"-1.5"}},"1":{"1":{"kf":"1.25","lv":"+1.5"},"2":{"kf":"1.13","lv":"+1.5"}},"6":{"1":{"kf":"2.02","lv":"+0.25"},"2":{"kf":"1.68","lv":"-0.25"}}}}';
            } elseif ($score == '1:1') {
                $evt = '{"1X2":{"1":{"kf":"4.11"},"2":{"kf":"1.41"},"3":{"kf":"3.82"}},"WXM":{"1":{"kf":"2.11"},"2":{"kf":"1.07"},"3":{"kf":"1.19"}},"T":{"1":{"1":{"kf":"1.34","lv":"2.5"},"2":{"kf":"1.98","lv":"2.5"}},"6":{"1":{"kf":"1.34","lv":"3.5"},"2":{"kf":"1.72","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"5":{"kf":"10.5"},"6":{"kf":"22"},"7":{"kf":"39"},"8":{"kf":"60"},"9":{"kf":"85"},"10":{"kf":"123"},"11":{"kf":"6.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"19":{"kf":"6"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"128"}}, "F":{"0":{"1":{"kf":"5.5","lv":"-1.5"},"2":{"kf":"3.7","lv":"-1.5"}},"1":{"1":{"kf":"1.25","lv":"+1.5"},"2":{"kf":"1.13","lv":"+1.5"}},"6":{"1":{"kf":"3.12","lv":"+0.25"},"2":{"kf":"1.48","lv":"-0.25"}}}}';
            } elseif ($score == '1:2') {
                $evt = '{"1X2":{"1":{"kf":"5.71"},"2":{"kf":"1.14"},"3":{"kf":"4.73"}},"WXM":{"1":{"kf":"3.41"},"2":{"kf":"1.11"},"3":{"kf":"1.09"}},"T":{"6":{"1":{"kf":"1.23","lv":"3.5"},"2":{"kf":"1.67","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"7":{"kf":"39"},"9":{"kf":"85"},"10":{"kf":"123"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"19":{"kf":"1.53"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"128"}},"F":{"0":{"1":{"kf":"5.5","lv":"-1.5"},"2":{"kf":"3.7","lv":"-1.5"}},"1":{"1":{"kf":"1.25","lv":"+1.5"},"2":{"kf":"1.13","lv":"+1.5"}},"6":{"1":{"kf":"3.92","lv":"+0.25"},"2":{"kf":"1.28","lv":"-0.25"}}}}';
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





<?php
define('bk', true);
@ini_set('display_errors', false);
require_once(__DIR__ . '/../classes/mysqli.php');
require_once("./../../config.php");

$curr_time = (new \DateTime('now'));//->add(new \DateInterval("PT60M"));

$id = '89731';

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
    if (in_array($minutes, [48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62])) {
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
                $evt = '{"1X2":{"1":{"kf":"2.12"},"2":{"kf":"2.38"},"3":{"kf":"3.86"}},"WXM":{"1":{"kf":"1.36"},"2":{"kf":"1.15"},"3":{"kf":"1.92"}},"T":{"0":{"1":{"kf":"1.43","lv":"2.0"},"2":{"kf":"2.12","lv":"2.0"}},"1":{"1":{"kf":"1.74","lv":"2.5"},"2":{"kf":"2.31","lv":"2.5"}},"6":{"1":{"kf":"2.17","lv":"3.5"},"2":{"kf":"3.55","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"1":{"kf":"1.2"},"2":{"kf":"13"},"3":{"kf":"25"},"4":{"kf":"67"},"5":{"kf":"12.5"},"6":{"kf":"21"},"7":{"kf":"27"},"8":{"kf":"59"},"9":{"kf":"81"},"10":{"kf":"138"},"11":{"kf":"7.5"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"19":{"kf":"9.8"},"20":{"kf":"21"},"21":{"kf":"33"},"22":{"kf":"55"},"23":{"kf":"75"},"24":{"kf":"132"}},"INDTOTAL1":{"5":{"1":{"kf":"1.68","lv":"1.5"},"2":{"kf":"2.21","lv":"1.5"}}},"INDTOTAL2":{"5":{"1":{"kf":"1.93","lv":"1.5"},"2":{"kf":"2.01","lv":"1.5"}}},"F":{"0":{"1":{"kf":"5.5","lv":"-1.5"},"2":{"kf":"3.7","lv":"-1.5"}},"1":{"1":{"kf":"1.25","lv":"+1.5"},"2":{"kf":"1.13","lv":"+1.5"}},"6":{"1":{"kf":"1.83","lv":"+0.25"},"2":{"kf":"1.77","lv":"-0.25"}}}}';
            } elseif ($score == '2:0') {
                $evt = '{"1X2":{"1":{"kf":"1.24"},"2":{"kf":"3.98"},"3":{"kf":"4.52"}},"WXM":{"1":{"kf":"1.09"},"2":{"kf":"1.04"},"3":{"kf":"2.15"}},"T":{"1":{"1":{"kf":"1.14","lv":"2.5"},"2":{"kf":"2.31","lv":"2.5"}},"6":{"1":{"kf":"1.12","lv":"3.5"},"2":{"kf":"3.55","lv":"3.5"}}},"TOTALYNCHET":{"1":{"kf":"1.9"},"2":{"kf":"1.9"}},"SCR0":{"2":{"kf":"2.31"},"3":{"kf":"26"},"4":{"kf":"67"},"5":{"kf":"12.5"},"6":{"kf":"21"},"7":{"kf":"27"},"8":{"kf":"59"},"9":{"kf":"81"},"10":{"kf":"138"},"12":{"kf":"15.5"},"13":{"kf":"65"},"14":{"kf":"250"},"23":{"kf":"75"},"24":{"kf":"132"}},"F":{"0":{"1":{"kf":"5.5","lv":"-1.5"},"2":{"kf":"3.7","lv":"-1.5"}},"1":{"1":{"kf":"1.25","lv":"+1.5"},"2":{"kf":"1.13","lv":"+1.5"}},"6":{"1":{"kf":"1.83","lv":"+0.25"},"2":{"kf":"1.77","lv":"-0.25"}}}}';
            }

            if(!$timeout) {
                $db->query('UPDATE `events` SET `is_live` = "?s", `bets` = "?s", `min` = "?s", `curr_time` = "?s", score = "?s" WHERE `events`.`id` = "?s";', 1, $evt, $minutes, $curr_time->format('Y-m-d H:i:s'), $score, $id);
            }        }
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





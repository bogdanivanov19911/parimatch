<?php
define('bk', true);
header('Content-Type: text/html; charset=utf-8');
@ini_set('display_errors', false);
@ini_set('html_errors', false);
define('MAX_FILE_SIZE', 99999999999);
set_time_limit(0);

require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/function.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/mysqli.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

date_default_timezone_set("UTC");

$result = file_get_contents("http://185.43.223.70/engine/cron/api/data/result2.json");
$liveEvent = json_decode($result, true);

$curr_time = (new \DateTime('now'));//->add(new \DateInterval("PT60M"));


foreach ($liveEvent["reply"]["sports"] as $key => $value) {
    foreach ($value["chmps"] as $key2 => $value2) {
        foreach ($value2["evts"] as $key3 => $value3) {
            $queryEvent = $db->query('SELECT `id`,`time_start`,`parse_id` FROM `events` WHERE `parse_id` = "?i" AND `result` IS NULL LIMIT 1', $value3["id_ev"]);
            $rowEvent = $queryEvent->fetch_assoc();

            if ($db->getAffectedRows() == 1) {
                if ($key == 74 OR $key == 39 OR $key == 20) {
                    if ($value3["name_ht"] == $value3["sc_ev"]) {
                        $value3["sc_ev"] = "1:0";
                    } elseif ($value3["name_at"] == $value3["sc_ev"]) {
                        $value3["sc_ev"] = "0:1";
                    } elseif ($value3["sc_ev"] == "draw") {
                        $value3["sc_ev"] = "1:1";
                    }

                    if (!empty($value3["ext"])) {
                        foreach ($value3["ext"] as $key4 => $value4) {
                            if ($value4["name_ext"] == "Тотал раундов") {
                                $value3["sc_ext_ev"] = $value4["value_ext"] . ":0, 0:0";
                            }
                        }
                    }
                }

                if (!empty($value3["sc_ev"]) OR !empty($value3["sc_ext_ev"])) {
                    $db->query("UPDATE `events` SET `result` = 1, `score` = '?s', `score_all` = '?s', `curr_time` = '?s' WHERE `parse_id` = '?i' LIMIT 1", $value3["sc_ev"], $value3["sc_ext_ev"], $curr_time->format('Y-m-d H:i:s'), $value3["id_ev"]);
                }
            }
        }
    }
}

?>
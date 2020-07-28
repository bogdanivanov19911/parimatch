<?php
header('Content-Type: application/json; charset=utf-8');
define('bk', true);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/function.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/mysqli.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

function limitBet($factor, $maxbet)
{
    $percent = 1 / $factor; // Процент победы "0.55"
    $limit_now = $maxbet * $percent;

    return round($limit_now);
}

sleep(3);

$namesArray = array(
    "1X2" => array(
        "title" => "Победа в матче: ",
        "teams" => TRUE,
        "name_ex" => array(
            "3" => "Ничья",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "WXM" => array(
        "title" => "Двойной исход: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "1X",
            "2" => "12",
            "3" => "X2",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "T" => array(
        "title" => "Тотал: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Меньше",
            "2" => "Больше",
        ),
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "F" => array(
        "title" => "Фора: ",
        "teams" => TRUE,
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "OZIN12TYME" => array(
        "title" => "Голы в обоих таймах: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "OZTEAM" => array(
        "title" => "Обе забьют: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "GOALS1" => array(
        "title" => "Команда 1 забьет гол: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "GOALS2" => array(
        "title" => "Команда 2 забьет гол: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "1TIMEHAVEGOALS" => array(
        "title" => "1 тайм будут голы: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "2TIMEHAVEGOALS" => array(
        "title" => "2 тайм будут голы: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "TIMERESULTS" => array(
        "title" => "Результативность таймов: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "1>2",
            "2" => "1<2",
            "3" => "1=2",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "TOTALYNCHET" => array(
        "title" => "Тотал чет/нечет: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Чет",
            "2" => "Нечет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "INDTOTAL1" => array(
        "title" => "Индивидуальный тотал Команда 1: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Меньше",
            "2" => "Больше",
        ),
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "INDTOTAL2" => array(
        "title" => "Индивидуальный тотал Команда 2: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Меньше",
            "2" => "Больше",
        ),
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "OZIN1TYME" => array(
        "title" => "Обе забьют в первом тайме: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "OZIN2TYME" => array(
        "title" => "Обе забьют во втором тайме: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "TEAM1GOALSIN12TIME" => array(
        "title" => "Команда 1 забьет в обоих таймах: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "TEAM2GOALSIN12TIME" => array(
        "title" => "Команда 2 забьет в обоих таймах: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "OZANDTOTALUNDER" => array(
        "title" => "Обе забьют и тотал меньше: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "SCR0" => array(
        "title" => "Точный счет: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "1:0",
            "2" => "2:0",
            "3" => "3:0",
            "4" => "4:0",
            "5" => "2:1",
            "6" => "3:1",
            "7" => "3:2",
            "8" => "4:1",
            "9" => "4:2",
            "10" => "4:3",
            "11" => "1:1",
            "12" => "2:2",
            "13" => "3:3",
            "14" => "4:4",
            "15" => "0:1",
            "16" => "0:2",
            "17" => "0:3",
            "18" => "0:4",
            "19" => "1:2",
            "20" => "1:3",
            "21" => "2:3",
            "22" => "1:4",
            "23" => "2:4",
            "24" => "3:4",
        ),
        "for" => FALSE,
        "lv" => true,
    ),
    "OZANDTOTALOVER" => array(
        "title" => "Обе забьют и тотал больше: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => TRUE,
        "lv" => TRUE,
    ),
    "W1ANDOZ" => array(
        "title" => "Команда 1 победит и обе забьют: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
    "W2ANDOZ" => array(
        "title" => "Команда 2 победит и обе забьют: ",
        "teams" => FALSE,
        "name_ex" => array(
            "1" => "Да",
            "2" => "Нет",
        ),
        "for" => FALSE,
        "lv" => FALSE,
    ),
);

$standart_limits = 1000000;

$standart_limits100 = $standart_limits * 100;

date_default_timezone_set("UTC");

$date = date_create(date("Y-m-d H:i:s"));
$_COOKIE['hour'] = "0";

$date->modify("+3 hour");
$date_now = date_format($date, "Y-m-d H:i:s");
$time_now = date_format($date, "H:i");

$db->setTypeMode(Database_Mysql::MODE_TRANSFORM);


$d1 = strtotime(date("Y-m-d H:i:s")) - 35;

$curr_timeLive = date("Y-m-d H:i:s", $d1);

$date_events = date("Y-m-d H:i:s");

if (isset($_COOKIE['hash'])) {
    $query = $db->query('SELECT `id`,`login`,`hash`,`email`,`name`,`surname`,`balance`,INET_NTOA(ip) FROM `users` WHERE `hash` = "?s" AND `id` = "?i"', $_COOKIE['hash'], $_COOKIE['id']);
    $user = $query->fetch_assoc();

    if ($db->getAffectedRows() == 1) {
        $logged = TRUE;
    } else {
        $logged = FALSE;
        exit();
    }
} else {
    $logged = FALSE;
    exit();
}

if ($_POST["type"] == 1 OR $_POST["type"] == 2) {

} else {
    $_POST["type"] = 1;
}

if (count($_POST["dataid"]) == count($_POST["dataresult"]) and count($_POST["dataprice"]) == count($_POST["datafactor"]) and count($_POST["dataid"]) == count($_POST["datateams"])) {
    if ($_POST["type"] == "1") {
        $i = 0;
        foreach ($_POST["dataid"] as $value) {
            $dataId = preg_replace('/[^0-9]/', '', $value);
            $dataResult = preg_replace('/[^0-9]/', '', $_POST["dataresult"][$i]);
            $dataPrice = preg_replace('/[^0-9.,]/', '', str_replace(",", ".", $_POST["dataprice"][$i]));
            $dataFactor = preg_replace('/[^0-9.,]/', '', $_POST["datafactor"][$i]);
            $dataTeams = str_replace(",", "", $_POST["datateams"][$i]);
            $dataFinder = preg_replace("/[^a-zA-Z0-9+]$/u", '', $_POST["datafinder"][$i]);
            $dataLv = preg_replace("/[^-0-9]+$/", '', $_POST["datalv"][$i]);

            $newLimits = limitBet($dataFactor, $standart_limits);
            $newLimits100 = $newLimits * 100;

            $query = $db->query("SELECT * FROM `events` WHERE `id` = '?i' and `result` IS NULL", $dataId);
            $row3 = $query->fetch_assoc();

            $game_query = $db->query('SELECT `name` FROM `games` WHERE `id` = "?i" LIMIT 1', $row3['game_id']);
            $game_row = $game_query->fetch_assoc();
            $teams = $game_row["name"] . ". " . $row3["name_1"] . " - " . $row3["name_2"];

            $evt = json_decode($row3["bets"], true);

            if ($db->getAffectedRows() == 1) {
                if ($dataPrice >= "2" and $dataPrice <= $newLimits) {
                    if ($dataPrice <= $user["balance"] / 100) {
                        if ($namesArray[$dataFinder]["for"] == TRUE) {
                            foreach ($evt[$dataFinder] as $key3 => $value3) {
                                foreach ($value3 as $key4 => $value4) {
                                    if ($value4["lv"] == $dataLv AND $key4 == $dataResult) {
                                        if ($value4["kf"] == $dataFactor OR $_POST["editfactor"] == 1) {
                                            $select_bet = $value4;
                                            $select_bet["result"] = $dataResult;
                                            $select_bet["title"] = $namesArray[$dataFinder]["title"];
                                            $select_bet["type"] = $dataFinder;
                                            $select_bet["lv"] = $dataLv;
                                            unset($error[$i]);
                                            break 2;
                                        } else {
                                            $error[$i] = $teams . " - коэффициент изменился!";
                                        }
                                    } else {
                                        $error[$i] = $teams . " - этот исход уже не доступен для ставки!";
                                    }
                                }
                            }
                            if ($namesArray[$dataFinder]["lv"] == FALSE) {
                                unset($select_bet["lv"]);
                            }
                            if ($namesArray[$dataFinder]["teams"] == FALSE) {
                                if (!empty($namesArray[$dataFinder]["name_ex"][$dataResult])) {
                                    $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                } else {
                                    $select_bet["name"] = "";
                                }
                            } else {
                                if (!empty($row3["name_" . $dataResult])) {
                                    $select_bet["name"] = $row3["name_" . $dataResult];
                                } else {
                                    $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                }
                            }
                        } else {
                            if (!empty($evt[$dataFinder][$dataResult]["kf"])) {
                                if ($evt[$dataFinder][$dataResult]["kf"] == $dataFactor OR $_POST["editfactor"] == 1) {
                                    $select_bet = $evt[$dataFinder][$dataResult];
                                    $select_bet["result"] = $dataResult;
                                    $select_bet["title"] = $namesArray[$dataFinder]["title"];
                                    $select_bet["type"] = $dataFinder;
                                    $select_bet["lv"] = $dataLv;
                                    if ($namesArray[$dataFinder]["lv"] == FALSE) {
                                        unset($select_bet["lv"]);
                                    }
                                    if ($namesArray[$dataFinder]["teams"] == FALSE) {
                                        if (!empty($namesArray[$dataFinder]["name_ex"][$dataResult])) {
                                            $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                        } else {
                                            $select_bet["name"] = "";
                                        }
                                    } else {
                                        if (!empty($row3["name_" . $dataResult])) {
                                            $select_bet["name"] = $row3["name_" . $dataResult];
                                        } else {
                                            $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                        }
                                    }
                                } else {
                                    $error[$i] = $teams . " - коэффициент изменился!";
                                }
                            } else {
                                $error[$i] = $teams . " - этот исход уже не доступен для ставки!";
                            }
                        }

                        if ($row3["is_live"] == 1) {
                            if ($curr_timeLive > $row3["curr_time"]) {
                                $error[$i] = $teams . " - прием ставок на матч временно ограничен!";
                            }
                        } else {
                            if ($date_now > $row3["time_start"]) {
                                $error[$i] = $teams . " - матч начался!";
                            }
                        }

                        if ($select_bet["kf"] <= 1 OR empty($select_bet["kf"])) {
                            $error[] = $teams . " - прием ставок на матч остановлен!";
                        }

                        if (empty($error[$i])) {
                            $winner = $select_bet["title"] . " " . $select_bet["name"] . " ";
                            $factor = $select_bet["kf"] * 100;

                            $evt_json = array();

                            $evt_json[$dataId] = array(
                                "type" => $select_bet["type"],
                                "rate" => $select_bet["result"],
                                "kf" => $select_bet["kf"],
                                "lv" => $select_bet["lv"]
                            );

                            $evt_json = json_encode($evt_json);

                            $db->query("INSERT INTO `placed_bet`(`id_bets`,`teams`,`winner`,`bets`,`user_id`,`type`,`factor`,`price`,`rate`,`bet_result`,`bet_status`,`date_add`) VALUES('?i','?s','?s','?s','?i','?i','?i','?i','?s','0','0','?s')",
                                $dataId, $teams, $winner, $evt_json, $user["id"], "1", $factor, $dataPrice * 100, $dataResult, $date_now);
                            $db->query("UPDATE `users` SET `balance` = `balance` - " . $dataPrice * 100 . " WHERE `hash` = '?s' AND `id` = '?i'", $_COOKIE['hash'], $_COOKIE['id']);


                            $unresolved[] = $dataPrice * 100;
                            $success[] = $dataTeams . ": <div class='c-green' style='display: inline-block;'>" . $winner . "</div> - ставка принята";

                        }
                    } else {
                        $error[$i] = $dataTeams . " - недостаточно баланса для ставки!";
                    }
                } else {
                    $error[$i] = $dataTeams . " - минимальная ставка: 2, максимальная: " . $newLimits;
                }
            } else {
                $error[$i] = $dataTeams . " - событие не найдено!";
            }

            $i++;
        }
    } elseif ($_POST["type"] == "2") {
        if (count($_POST["dataid"]) < 2) $error[] = "В экспрессе должно быть более 2х событий!";

        $dataPrice = preg_replace('/[^0-9.,]/', '', str_replace(",", ".", $_POST["price"]));

        $i = 0;
        foreach ($_POST["dataid"] as $value) {
            $dataId = preg_replace('/[^0-9]/', '', $value);
            $dataResult = preg_replace('/[^0-9]/', '', $_POST["dataresult"][$i]);
            $dataFactor = preg_replace('/[^0-9.,]/', '', $_POST["datafactor"][$i]);
            $dataTeams = str_replace(",", "", $_POST["datateams"][$i]);
            $dataFinder = preg_replace("/[^a-zA-Z0-9+]$/u", '', $_POST["datafinder"][$i]);
            $dataLv = preg_replace("/[^-0-9]+$/", '', $_POST["datalv"][$i]);

            $query = $db->query("SELECT * FROM `events` WHERE `id` = '?i' and `result` IS NULL", $dataId);
            $row3 = $query->fetch_assoc();
            $evt = json_decode($row3["bets"], true);

            if ($db->getAffectedRows() == 1) {

                $game_query = $db->query('SELECT `name` FROM `games` WHERE `id` = "?i" LIMIT 1', $row3['game_id']);
                $game_row = $game_query->fetch_assoc();

                $teams = $game_row["name"] . ". " . $row3["name_1"] . " - " . $row3["name_2"];

                if ($namesArray[$dataFinder]["for"] == TRUE) {
                    foreach ($evt[$dataFinder] as $key3 => $value3) {
                        foreach ($value3 as $key4 => $value4) {
                            if ($value4["lv"] == $dataLv AND $key4 == $dataResult) {
                                if ($value4["kf"] == $dataFactor OR $_POST["editfactor"] == "1") {
                                    $select_bet = $value4;
                                    $select_bet["result"] = $dataResult;
                                    $select_bet["title"] = $namesArray[$dataFinder]["title"];
                                    $select_bet["type"] = $dataFinder;
                                    $select_bet["lv"] = $dataLv;
                                    unset($error[$i]);
                                    break 2;
                                } else {
                                    $error[$i] = $teams . " - коэффициент изменился!";
                                }
                            } else {
                                $error[$i] = $teams . " - этот исход уже не доступен для ставки!";
                            }
                        }
                    }
                    if ($namesArray[$dataFinder]["lv"] == FALSE) {
                        unset($select_bet["lv"]);
                    }
                    if ($namesArray[$dataFinder]["teams"] == FALSE) {
                        if (!empty($namesArray[$dataFinder]["name_ex"][$dataResult])) {
                            $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                        } else {
                            $select_bet["name"] = "";
                        }
                    } else {
                        if (!empty($row3["name_" . $dataResult])) {
                            $select_bet["name"] = $row3["name_" . $dataResult];
                        } else {
                            $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                        }
                    }
                } else {
                    if (!empty($evt[$dataFinder][$dataResult]["kf"])) {
                        if ($evt[$dataFinder][$dataResult]["kf"] == $dataFactor OR $_POST["editfactor"] == 1) {
                            $select_bet = $evt[$dataFinder][$dataResult];
                            $select_bet["result"] = $dataResult;
                            $select_bet["title"] = $namesArray[$dataFinder]["title"];
                            $select_bet["type"] = $dataFinder;
                            $select_bet["lv"] = $dataLv;
                            if ($namesArray[$dataFinder]["lv"] == FALSE) {
                                unset($select_bet["lv"]);
                            }
                            if ($namesArray[$dataFinder]["teams"] == FALSE) {
                                if (!empty($namesArray[$dataFinder]["name_ex"][$dataResult])) {
                                    $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                } else {
                                    $select_bet["name"] = "";
                                }
                            } else {
                                if (!empty($row3["name_" . $dataResult])) {
                                    $select_bet["name"] = $row3["name_" . $dataResult];
                                } else {
                                    $select_bet["name"] = $namesArray[$dataFinder]["name_ex"][$dataResult];
                                }
                            }
                        } else {
                            $error[$i] = $teams . " - коэффициент изменился!";
                        }
                    } else {
                        $error[$i] = $teams . " - этот исход уже не доступен для ставки!";
                    }
                }

                if ($row3["is_live"] == 1) {
                    if ($curr_timeLive > $row3["curr_time"]) {
                        $error[$i] = $teams . " - прием ставок на матч временно ограничен!";
                    }
                } else {
                    if ($date_now > $row3["time_start"]) {
                        $error[$i] = $teams . " - матч начался!";
                    }
                }

                if ($select_bet["kf"] <= 1 OR empty($select_bet["kf"])) {
                    $error[$i] = $teams . " - прием ставок на матч остановлен!";
                }

                if (empty($error[$i])) {
                    $winner = $select_bet["title"] . " " . $select_bet["name"] . " " . $select_bet["lv"];
                    $factor = $select_bet["kf"] * 100;
                    $select_bet["teams"] = $teams;

                    $express_bet[$dataId] = $select_bet;
                }
            } else {
                $error[$i] = $dataTeams . " - событие не найдено!";
            }
            $i++;
        }

        if ($dataPrice >= "2" and $dataPrice <= 15000) {
            if ($dataPrice <= $user["balance"] / 100) {
                if (empty($error)) {
                    foreach ($express_bet as $key => $value) {
                        $id_bets .= $key . ",";
                        $teams_string .= $value["teams"] . ",";
                        $winner_string .= $value["title"] . " " . $value["name"] . " " . $value["lv"] . ",";
                        $factor_string .= $value["kf"] * 100 . ",";
                        $rate_string .= $value["result"] . ",";
                        $result_string .= "0,";
                    }

                    $id_bets = chop($id_bets, ",");
                    $teams_string = chop($teams_string, ",");
                    $winner_string = chop($winner_string, ",");
                    $factor_string = chop($factor_string, ",");
                    $rate_string = chop($rate_string, ",");
                    $result_string = chop($result_string, ",");

                    $express_bet = json_encode($express_bet);

                    $db->query("INSERT INTO `placed_bet`(`id_bets`,`teams`,`winner`,`bets`,`user_id`,`type`,`factor`,`price`,`rate`,`bet_result`,`bet_status`,`date_add`) VALUES('?s','?s','?s','?s','?i','?i','?s','?i','?s','?s','0','?s')",
                        $id_bets, $teams_string, $winner_string, $express_bet, $user["id"], "2", $factor_string, $dataPrice * 100, $rate_string, $result_string, $date_now);

                    $db->query("UPDATE `users` SET `balance` = `balance` - " . $dataPrice * 100 . " WHERE `hash` = '?s' AND `id` = '?i'", $_COOKIE['hash'], $_COOKIE['id']);

                    $success[] = "<div class='c-green' style='display: inline-block;'>Экспресс принят!</div>";
                    $unresolved[] = $dataPrice * 100;
                }
            } else {
                $error[] = "Недостаточно баланса для ставки!";
            }
        } else {
            $error[] = "Минимальная ставка 2, максимальная - 15000";
        }


    } else {
        exit();
    }
} else {
    exit();
}

if (!empty($unresolved)) {
    for ($i = 0; $i <= count($unresolved); $i++) {
        $unresolved_count += $unresolved[$i];
    }

    $unresolved_count = $unresolved_count / 100;
} else {
    $unresolved_count = "0";
}

$balance_now = ($user["balance"] / 100) - $unresolved_count;

$errArr = array();

if ($error == $errArr) {
    unset($error);
}

$body = array('error' => $error, 'success' => $success, 'price' => $unresolved_count, 'balance' => $balance_now, 'ed' => $balance_now);
echo json_encode($body);
?>
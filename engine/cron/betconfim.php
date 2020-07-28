<?php
define('bk', true);
header('Content-Type: text/html; charset=utf-8');
@ini_set('display_errors', true);
@ini_set('html_errors', true);
define('MAX_FILE_SIZE', 99999999999);
set_time_limit(0);


require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/function.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/mysqli.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");






function bet1X2($score1, $score2) {
    if($score1 > $score2) {
        $result = 1;
    } elseif($score1 < $score2) {
        $result = 2;
    } elseif($score1 == $score2) {
        $result = 3;
    } else {
        $result = 0;
    }

    return $result;
}

function betWXM($score1, $score2, $rate) {
    if($rate == 1) {
        if($score1 > $score2 OR $score1 == $score2) {
            return $rate;
        } else {
            return 4;
        }
    } elseif($rate == 2) {
        if(($score1 > $score2 OR $score1 < $score2) AND $score1 != $score2) {
            return $rate;
        } else {
            return 4;
        }
    } elseif($rate == 3) {
        if(($score1 < $score2 OR $score1 == $score2) AND $score1 < $score2) {
            return $rate;
        } else {
            return 4;
        }
    }
}

function betTotal($score1, $score2, $lv) {
    $commonTotal = $score1 + $score2;
    if($lv < $commonTotal) {
        $result = 2;
    } elseif($lv > $commonTotal) {
        $result = 1;
    } else {
        $result = "REJECT";
    }
    return $result;
}

function betFora($score1, $score2, $lv, $rate) {
    if(substr($lv, 0, 1) == "-") {
        if($rate == 1) {
            $score1 = $score1 - str_replace("-","",$lv);
        } else {
            $score2 = $score2 - str_replace("-","",$lv);
        }
    } else {
        if($rate == 1) {
            $score1 = $score1 + $lv;
        } else {
            $score2 = $score2 + $lv;
        }
    }

    if($score1 > $score2) {
        $result = 1;
    } elseif($score1 < $score2) {
        $result = 2;
    } else {
        $result = "REJECT";
    }

    return $result;
}

















$query = $db->query("SELECT * FROM `placed_bet` WHERE `bet_status` = '0'");
$row = $query->fetch_assoc_array();

foreach($row as $key => $value) {
    $ev = json_decode($value["bets"], true);

    if($value["type"] == 1) {
        foreach($ev as $key2 => $value2) {
            if($value["bet_result"] == "0") {
                $query2 = $db->query("SELECT `id`, `score`, `score_all`,`game_id`,`need_score` FROM `events` WHERE `id` = '?i' AND `result` IS NOT NULL LIMIT 1", $key2);
                $row2 = $query2->fetch_assoc();

                if(!empty($row2["id"])) {
                    unset($timeArray44,$allScoreMatch1,$allScoreMatch2,$scoreOneTeamTime,$scoreTwoTeamTime,$scoreOneTeam,$scoreTwoTeam,$bet_result);

                    $scoreExplode = explode(":",$row2["score"]);
                    $scoreOneTeam = $scoreExplode[0];
                    $scoreTwoTeam = $scoreExplode[1];

                    $matchTimeArray = array();

                    $timeArray44 = explode("(",$row2["score_all"]);
                    $timeArray = explode(", ",$timeArray44[0]);
                    $timeU = 1;
                    foreach($timeArray as $key12 => $value12) {
                        $scoreExplodeTime = explode(":",$value12);

                        $matchTimeArray[$timeU]["OneTeam"] = $scoreExplodeTime[0];
                        $matchTimeArray[$timeU]["TwoTeam"] = $scoreExplodeTime[1];

                        $allScoreMatch1 += $scoreExplodeTime[0];
                        $allScoreMatch2 += $scoreExplodeTime[1];
                        $timeU++;
                    }

                    if($row2["game_id"] == 5 OR $row2["game_id"] == 13 OR $row2["game_id"] == 34 OR ($row2["game_id"] == 27 AND $row2["need_score"] != NULL)) {
                        $allScoreMatch1 = $scoreOneTeam;
                        $allScoreMatch2 = $scoreTwoTeam;

                        $matchTimeArray[2]["OneTeam"] = $scoreOneTeam - $matchTimeArray[1]["OneTeam"];
                        $matchTimeArray[2]["TwoTeam"] = $scoreTwoTeam - $matchTimeArray[1]["TwoTeam"];
                    }











                    if($value2["type"] == "1X2") {
                        $bet_result = bet1X2($scoreOneTeam,$scoreTwoTeam);
                    } elseif($value2["type"] == "T") {
                        $bet_result = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                    } elseif($value2["type"] == "F") {
                        $bet_result = betFora($allScoreMatch1,$allScoreMatch2,$value2["lv"],$value2["rate"]);
                    } elseif($value2["type"] == "WXM") {
                        $bet_result = betWXM($scoreOneTeam,$scoreTwoTeam,$value2["rate"]);
                    } elseif($value2["type"] == "OZIN12TYME") {
                        if(($matchTimeArray[1]["OneTeam"] > 0 OR $matchTimeArray[1]["TwoTeam"] > 0) AND ($matchTimeArray[2]["OneTeam"] > 0 OR $matchTimeArray[2]["TwoTeam"] > 0)) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZTEAM") {
                        if($allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "GOALS1") {
                        if($scoreOneTeam > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "GOALS2") {
                        if($scoreTwoTeam > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "1TIMEHAVEGOALS") {
                        if($matchTimeArray[1]["OneTeam"] > 0 OR $matchTimeArray[1]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "2TIMEHAVEGOALS") {
                        if($matchTimeArray[2]["OneTeam"] > 0 OR $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TIMERESULTS") {
                        if(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) == ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 3;
                        } elseif(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) > ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 1;
                        } elseif(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) < ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TOTALYNCHET") {
                        $commonTotal = $allScoreMatch1 + $allScoreMatch2;

                        if(($commonTotal%2)) {
                            $bet_result = 2;
                        } else {
                            $bet_result = 1;
                        }
                    } elseif($value2["type"] == "INDTOTAL1") {
                        if($value2["lv"] < $allScoreMatch1) {
                            $bet_result = 2;
                        } elseif($value2["lv"] > $allScoreMatch1) {
                            $bet_result = 1;
                        } else {
                            $bet_result = "REJECT";
                        }
                    } elseif($value2["type"] == "INDTOTAL2") {
                        if($value2["lv"] < $allScoreMatch2) {
                            $bet_result = 2;
                        } elseif($value2["lv"] > $allScoreMatch2) {
                            $bet_result = 1;
                        } else {
                            $bet_result = "REJECT";
                        }
                    } elseif($value2["type"] == "OZIN1TYME") {
                        if($matchTimeArray[1]["OneTeam"] > 0 AND $matchTimeArray[1]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZIN2TYME") {
                        if($matchTimeArray[2]["OneTeam"] > 0 AND $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif ($value2["type"] == "OZIN12TYME") {
                        if(($matchTimeArray[1]["OneTeam"] > 0 OR $matchTimeArray[1]["TwoTeam"] > 0) && ($matchTimeArray[2]["OneTeam"] > 0 OR $matchTimeArray[2]["TwoTeam"] > 0)) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TEAM1GOALSIN12TIME") {
                        if($matchTimeArray[1]["OneTeam"] > 0 AND $matchTimeArray[2]["OneTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TEAM2GOALSIN12TIME") {
                        if($matchTimeArray[1]["TwoTeam"] > 0 AND $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZANDTOTALUNDER") {
                        $bet_ttt = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                        if($bet_ttt == 1 AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZANDTOTALOVER") {
                        $bet_ttt = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                        if($bet_ttt == 2 AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "W1ANDOZ") {
                        if($scoreOneTeam > $scoreTwoTeam AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "W2ANDOZ") {
                        if($scoreOneTeam < $scoreTwoTeam AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "SCR0") {
                        $type_score = $allScoreMatch1.":".$allScoreMatch2;

                        if($value2["lv"] == $type_score) {
                            $bet_result = $value2["rate"];
                        } else {
                            $bet_result = 21;
                        }
                    }




















                    if(!empty($bet_result)) {
                        $db->query("UPDATE `placed_bet` SET `bet_result` = '?s' WHERE `id` = '?i'", $bet_result, $value["id"]);
                    }
                }
            } elseif($value["rate"] == $value["bet_result"]) {
                $query = $db->query('SELECT `id`,`win_balance`,`balance_bonus`,`status` FROM `users` WHERE `id` = "?i" LIMIT 1', $value["user_id"]);
                $user = $query->fetch_assoc();

                $db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $value["id"]);

                $win = ($value["price"] * $value["factor"]) / 100;

                $user["win_balance"] = $user["win_balance"] + $win;

              //  $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
               //     $value["user_id"],$win / 100 , "Выиграшная ставка №".$value["id"].". Сумма: +".$win / 100 , date("Y-m-d H:i:s") , 1);

                $db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $user["win_balance"], $value["user_id"]);


            } elseif($value["bet_result"] == "REJECT") {
                $query = $db->query('SELECT `id`,`balance`,`balance_bonus`,`status` FROM `users` WHERE `id` = "?i" LIMIT 1', $value["user_id"]);
                $user = $query->fetch_assoc();

                $db->query("UPDATE `placed_bet` SET `bet_status` = '4' WHERE `id` = '?i'", $value["id"]);

                $win = $value["price"];
                $user["balance"] = $user["balance"] + $win;
                $db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $user["balance"], $value["user_id"]);

                $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
                    $value["user_id"],$win / 100 , "Возврат ставки №".$value["id"].". Сумма: +=".$win / 100 , date("Y-m-d H:i:s") , 4);

            } else {

                $query = $db->query('SELECT `id`,`balance`,`balance_bonus`,`status` FROM `users` WHERE `id` = "?i" LIMIT 1', $value["user_id"]);
                $user = $query->fetch_assoc();

                if(!empty($user["balance_bonus"])) {
                    $bonus_amount = $value["price"] * "0.05"; // SUMM BONUS

                    if($user["balance_bonus"] >= $bonus_amount) {
                        $win = $bonus_amount;
                        $user["balance_bonus"] = $user["balance_bonus"] - $bonus_amount;
                        $db->query("UPDATE `users` SET `balance_bonus` = '?i' WHERE `id` = '?i'", $user["balance_bonus"], $value["user_id"]);

                    //    $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
                    //        $value["user_id"],$bonus_amount / 100 , "Бонус 5% от проигранной ставки. Сумма: ".$bonus_amount / 100 , date("Y-m-d H:i:s") , 1);

                        $user["balance"] = $user["balance"] + $win;

                        $db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $user["balance"], $value["user_id"]);
                    }
                }


                $win = $value["price"];

              //  $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
               //     $value["user_id"],$win / 100 , "Проигранная ставка №".$value["id"].". Сумма: -".$win / 100 , date("Y-m-d H:i:s") , 2);

                $db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $value["id"]);
            }
        }
    } elseif($value["type"] == 2) {
        unset($timeArray44,$allScoreMatch1,$allScoreMatch2,$scoreOneTeamTime,$scoreTwoTeamTime,$scoreOneTeam,$scoreTwoTeam,$bet_result,$matchTimeArray,$errorBet);

        $bet_result_array = explode(",",$value["bet_result"]);

        if(in_array("0", $bet_result_array)) {
            $id_bets = explode(",",$value["id_bets"]);

            $d=0;
            foreach($ev as $key2 => $value2) {
                $query2 = $db->query("SELECT `id`, `score`, `score_all`,`game_id` FROM `events` WHERE `id` = '?i' AND `result` IS NOT NULL LIMIT 1", $key2);
                $row2 = $query2->fetch_assoc();

                if(!empty($row2["id"])) {
                    $scoreExplode = explode(":",$row2["score"]);
                    $scoreOneTeam = $scoreExplode[0];
                    $scoreTwoTeam = $scoreExplode[1];

                    $matchTimeArray = array();

                    $timeArray44 = explode("(",$row2["score_all"]);
                    $timeArray = explode(", ",$timeArray44[0]);
                    $timeU = 1;
                    foreach($timeArray as $key12 => $value12) {
                        $scoreExplodeTime = explode(":",$value12);

                        $matchTimeArray[$timeU]["OneTeam"] = $scoreExplodeTime[0];
                        $matchTimeArray[$timeU]["TwoTeam"] = $scoreExplodeTime[1];

                        $allScoreMatch1 += $scoreExplodeTime[0];
                        $allScoreMatch2 += $scoreExplodeTime[1];
                        $timeU++;
                    }

                    if($row2["game_id"] == 5 OR $row2["game_id"] == 13 OR $row2["game_id"] == 34) {
                        $allScoreMatch1 = $scoreOneTeam;
                        $allScoreMatch2 = $scoreTwoTeam;

                        $matchTimeArray[2]["OneTeam"] = $scoreOneTeam - $matchTimeArray[1]["OneTeam"];
                        $matchTimeArray[2]["TwoTeam"] = $scoreTwoTeam - $matchTimeArray[1]["TwoTeam"];
                    }


















                    if($value2["type"] == "1X2") {
                        $bet_result = bet1X2($scoreOneTeam,$scoreTwoTeam);
                    } elseif($value2["type"] == "T") {
                        $bet_result = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                    } elseif($value2["type"] == "F") {
                        $bet_result = betFora($allScoreMatch1,$allScoreMatch2,$value2["lv"],$value2["rate"]);
                    } elseif($value2["type"] == "WXM") {
                        $bet_result = betWXM($scoreOneTeam,$scoreTwoTeam,$value2["rate"]);
                    } elseif($value2["type"] == "OZIN12TYME") {
                        if(($matchTimeArray[1]["OneTeam"] > 0 OR $matchTimeArray[1]["TwoTeam"] > 0) AND ($matchTimeArray[2]["OneTeam"] > 0 OR $matchTimeArray[2]["TwoTeam"] > 0)) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZTEAM") {
                        if($allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "GOALS1") {
                        if($scoreOneTeam > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "GOALS2") {
                        if($scoreTwoTeam > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "1TIMEHAVEGOALS") {
                        if($matchTimeArray[1]["OneTeam"] > 0 OR $matchTimeArray[1]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "2TIMEHAVEGOALS") {
                        if($matchTimeArray[2]["OneTeam"] > 0 OR $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TIMERESULTS") {
                        if(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) == ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 3;
                        } elseif(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) > ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 1;
                        } elseif(($matchTimeArray[1]["OneTeam"] + $matchTimeArray[1]["TwoTeam"]) < ($matchTimeArray[2]["OneTeam"] + $matchTimeArray[2]["TwoTeam"])) {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TOTALYNCHET") {
                        $commonTotal = $allScoreMatch1 + $allScoreMatch2;

                        if(($commonTotal%2)) {
                            $bet_result = 2;
                        } else {
                            $bet_result = 1;
                        }
                    } elseif($value2["type"] == "INDTOTAL1") {
                        if($value2["lv"] < $allScoreMatch1) {
                            $bet_result = 2;
                        } elseif($value2["lv"] > $allScoreMatch1) {
                            $bet_result = 1;
                        } else {
                            $bet_result = "REJECT";
                        }
                    } elseif($value2["type"] == "INDTOTAL2") {
                        if($value2["lv"] < $allScoreMatch2) {
                            $bet_result = 2;
                        } elseif($value2["lv"] > $allScoreMatch2) {
                            $bet_result = 1;
                        } else {
                            $bet_result = "REJECT";
                        }
                    } elseif($value2["type"] == "OZIN1TYME") {
                        if($matchTimeArray[1]["OneTeam"] > 0 AND $matchTimeArray[1]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZIN2TYME") {
                        if($matchTimeArray[2]["OneTeam"] > 0 AND $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TEAM1GOALSIN12TIME") {
                        if($matchTimeArray[1]["OneTeam"] > 0 AND $matchTimeArray[2]["OneTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "TEAM2GOALSIN12TIME") {
                        if($matchTimeArray[1]["TwoTeam"] > 0 AND $matchTimeArray[2]["TwoTeam"] > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZANDTOTALUNDER") {
                        $bet_ttt = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                        if($bet_ttt == 1 AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "OZANDTOTALOVER") {
                        $bet_ttt = betTotal($allScoreMatch1,$allScoreMatch2,$value2["lv"]);
                        if($bet_ttt == 2 AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "W1ANDOZ") {
                        if($scoreOneTeam > $scoreTwoTeam AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "W2ANDOZ") {
                        if($scoreOneTeam < $scoreTwoTeam AND $allScoreMatch1 > 0 AND $allScoreMatch2 > 0) {
                            $bet_result = 1;
                        } else {
                            $bet_result = 2;
                        }
                    } elseif($value2["type"] == "SCR0") {
                        $type_score = $allScoreMatch1.":".$allScoreMatch2;

                        if($value2["lv"] == $type_score) {
                            $bet_result = $value2["rate"];
                        } else {
                            $bet_result = 21;
                        }
                    }

























                    $bet_result_array[$d] = $bet_result;
                }

                $d++;
            }

            $bets_res = implode(",",$bet_result_array);

            $db->query("UPDATE `placed_bet` SET `bet_result` = '?s' WHERE `id` = '?i'",$bets_res,$value["id"]);
        } elseif($value["rate"] == $value["bet_result"]) {
            $factor_c = explode(",",$value["factor"]);

            foreach($factor_c as $value3) {
                $factor_array[] = $value3/100;
            }

            $factor = reset($factor_array);
            for($i=1,$c = count($factor_array);$i < $c;++$i) {
                $factor *= $factor_array[$i];
            }

            $factor = round($factor,2);

            $query = $db->query('SELECT `id`,`login`,`win_balance`,`balance_bonus`,`status` FROM `users` WHERE `id` = "?i" LIMIT 1', $value["user_id"]);
            $user = $query->fetch_assoc();

            $win = ($value["price"]/100 * $factor)*100;

            $express_logs = $user["login"]." | #id Express: ".$value["id"]." | Коєф: ".$factor." | Ставка: ". $value["price"]/100 . " | Выигрыш: " . $win /100 . "\n";

            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/engine/cron/api/data/express.txt", $express_logs, FILE_APPEND | LOCK_EX);

            $db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $value["id"]);

           // $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
            //    $value["user_id"],$win / 100 , "Выиграшная ставка №".$value["id"].". Сумма: +".$win / 100 , date("Y-m-d H:i:s") , 1);

            $user["win_balance"] = $user["win_balance"] + $win;
            $db->query("UPDATE `users` SET `win_balance` = '?i' WHERE `id` = '?i'", $user["win_balance"], $value["user_id"]);

            unset($factor_array,$factor_c);
        } elseif(in_array("REJECT", $bet_result_array)) {
            $bet_rate = explode(",",$value["rate"]);

            $ccc2 = 0;
            foreach($bet_result_array as $value12) {
                if($value12 == "REJECT" OR $value12 == $bet_rate[$ccc2]) {

                } else {
                    $errorBet = TRUE;
                }
                $ccc2++;
            }

            if($errorBet == TRUE) {
                $db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $value["id"]);
            } else {
                $factor_c = explode(",",$value["factor"]);

                $c = 0;
                foreach($bet_result_array as $value4) {
                    if($value4 == "REJECT") {
                        $factor_c[$c] = "100";
                    }
                    $c++;
                }

                foreach($factor_c as $value5) {
                    $factor_array[] = $value5/100;
                }

                $factor = reset($factor_array);
                for($i=1,$c = count($factor_array);$i < $c;++$i) {
                    $factor *= $factor_array[$i];
                }

                $factor = round($factor,2);

                $query = $db->query('SELECT `id`,`login`,`balance`,`status`,`balance_bonus` FROM `users` WHERE `id` = "?i" LIMIT 1', $value["user_id"]);
                $user = $query->fetch_assoc();

                $win = ($value["price"]/100 * $factor) * 100;

                $express_logs = $user["login"]." | #id Express: ".$value["id"]." | Коєф: ".$factor." | Ставка: ". $value["price"]/100 . " | Выигрыш: " . $win /100 . "\n";

                file_put_contents($_SERVER["DOCUMENT_ROOT"]."/engine/cron/api/data/express.txt", $express_logs, FILE_APPEND | LOCK_EX);

                $db->query("UPDATE `placed_bet` SET `bet_status` = '1' WHERE `id` = '?i'", $value["id"]);

             //   $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
             //       $value["user_id"],$win / 100 , "Выиграшная ставка №".$value["id"].". Сумма: +".$win / 100 , date("Y-m-d H:i:s") , 1);

                $user["balance"] = $user["balance"] + $win;
                $db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $user["balance"], $value["user_id"]);
            }
            unset($factor_array,$bet_rate,$factor_c);
        } else {
            $win = $value["price"];

          //  $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
           //     $value["user_id"],$win / 100 , "Проигранная ставка №".$value["id"].". Сумма: -".$win / 100 , date("Y-m-d H:i:s") , 2);

            $db->query("UPDATE `placed_bet` SET `bet_status` = '2' WHERE `id` = '?i'", $value["id"]);
        }


    }
}



?>
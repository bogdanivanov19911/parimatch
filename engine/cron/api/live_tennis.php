<?php
	define('bk', true);
	header('Content-Type: text/html; charset=utf-8');
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);
	define('MAX_FILE_SIZE', 999999999);
	set_time_limit(58);
	
	$start = microtime(true);
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/engine/classes/mysqli.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
	
	date_default_timezone_set("UTC");
	
	for ($i = 1; $i <= 60; $i++) {
		if((microtime(true) - $start) >= 56) {
			exit();
		}
	
		$data = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/engine/cron/api/data/live.json");
		$allEvent = json_decode($data,true);
		
		$game_id = 6;
        if(empty($allEvent["reply"]["sports"]["3"]["chmps"])) {
            continue;
        }
		foreach ($allEvent["reply"]["sports"]["2"]["chmps"] as $key2 => $value2) {
			$queryTour = $db->query('SELECT `id` FROM `tournaments` WHERE `name` = "?s" LIMIT 1',$value2["name_ch"]);
			$rowTour = $queryTour->fetch_assoc();
			
			if($db->getAffectedRows() == 1) {
				$tournament_id = $rowTour["id"];
			} else {
				$db->query("INSERT INTO `tournaments`(`name`,`sorting`,`game`) VALUES('?s','?i','?i')",$value2["name_ch"],$value2["order"],$game_id);
				
				$queryTour2 = $db->query('SELECT `id` FROM `tournaments` WHERE `name` = "?s" LIMIT 1',$value2["name_ch"]);
				$rowTour2 = $queryTour2->fetch_assoc();
				$tournament_id = $rowTour2["id"];
			}
			
			foreach ($value2["evts"] as $key3 => $value3) {
				$curr_time = date("Y-m-d H:i:s");
			
				$queryEvent = $db->query('SELECT `id` FROM `events` WHERE `parse_id` = "?i" LIMIT 1', $value3["id_ev"]);
				$rowEvent = $queryEvent->fetch_assoc();
				
				if($db->getAffectedRows() == 1) {
					unset($rrrr,$event);
					if(!empty($value3["t_md_min"])) {
						if($value3["time_name"] != "перерыв") {
							$rrrr = (date("i", time() - strtotime($value3["t_md_min"]))) + $value3["min"];
						} else {
							$rrrr = "пер";
						}
					} else {
						$rrrr = "";
					}
					
					$event = array();
					
					if(!empty($value3["main"]["69"])) {
						$event["1X2"]["1"]["kf"] = $value3["main"]["69"]["data"][$value3["id_ev"]]["blocks"]["Wm"]["P1"]["kf"];
						$event["1X2"]["2"]["kf"] = $value3["main"]["69"]["data"][$value3["id_ev"]]["blocks"]["Wm"]["P2"]["kf"];
                        if(!empty($value3["main"]["69"]["data"][$value3["id_ev"]]["blocks"]["Wm"]["X"]["kf"])) {
                            $event["1X2"]["3"]["kf"] = $value3["main"]["69"]["data"][$value3["id_ev"]]["blocks"]["Wm"]["X"]["kf"];
                        }					}
					
					if(!empty($value3["main"]["70"])) {
						$event["WXM"]["1"]["kf"] = $value3["main"]["70"]["data"][$value3["id_ev"]]["blocks"]["WXm"]["1X"]["kf"]; // 1X
						$event["WXM"]["2"]["kf"] = $value3["main"]["70"]["data"][$value3["id_ev"]]["blocks"]["WXm"]["12"]["kf"]; // 12
						$event["WXM"]["3"]["kf"] = $value3["main"]["70"]["data"][$value3["id_ev"]]["blocks"]["WXm"]["X2"]["kf"]; // X2
					}
					
					if(!empty($value3["main"]["71"])) {
						$event["F"][$value3["main"]["71"]["data"][$value3["id_ev"]]["order"]][1]["kf"] = $value3["main"]["71"]["data"][$value3["id_ev"]]["blocks"]["F1m"]["Kf_F1"]["kf"];
						$event["F"][$value3["main"]["71"]["data"][$value3["id_ev"]]["order"]][1]["lv"] = $value3["main"]["71"]["data"][$value3["id_ev"]]["blocks"]["F1m"]["Kf_F1"]["lv"];
						$event["F"][$value3["main"]["71"]["data"][$value3["id_ev"]]["order"]][2]["kf"] = $value3["main"]["71"]["data"][$value3["id_ev"]]["blocks"]["F1m"]["Kf_F2"]["kf"];
						$event["F"][$value3["main"]["71"]["data"][$value3["id_ev"]]["order"]][2]["lv"] = $value3["main"]["71"]["data"][$value3["id_ev"]]["blocks"]["F1m"]["Kf_F2"]["lv"];
					}
					
					if(!empty($value3["main"]["72"])) {
						$event["T"][$value3["main"]["72"]["data"][$value3["id_ev"]]["order"]][1]["kf"] = $value3["main"]["72"]["data"][$value3["id_ev"]]["blocks"]["T1m"]["Tm"]["kf"];
						$event["T"][$value3["main"]["72"]["data"][$value3["id_ev"]]["order"]][1]["lv"] = $value3["main"]["72"]["data"][$value3["id_ev"]]["blocks"]["T1m"]["Tm"]["lv"];
						$event["T"][$value3["main"]["72"]["data"][$value3["id_ev"]]["order"]][2]["kf"] = $value3["main"]["72"]["data"][$value3["id_ev"]]["blocks"]["T1m"]["Tb"]["kf"];
						$event["T"][$value3["main"]["72"]["data"][$value3["id_ev"]]["order"]][2]["lv"] = $value3["main"]["72"]["data"][$value3["id_ev"]]["blocks"]["T1m"]["Tm"]["lv"];
					}
					
					if(!empty($value3["ext"]["112"])) {
						foreach($value3["ext"]["112"]["data"] as $key4 => $value4) {
							$event["T"][$value4["order"]][1]["kf"] = $value4["blocks"]["T"]["Tm"]["kf"];
							$event["T"][$value4["order"]][1]["lv"] = $value4["blocks"]["T"]["Tm"]["lv"];
							$event["T"][$value4["order"]][2]["kf"] = $value4["blocks"]["T"]["Tb"]["kf"];
							$event["T"][$value4["order"]][2]["lv"] = $value4["blocks"]["T"]["Tm"]["lv"];
						}
					}
					
					if(!empty($value3["ext"]["71"])) {
						foreach($value3["ext"]["71"]["data"] as $key4 => $value4) {
							$event["F"][$value4["order"]][1]["kf"] = $value4["blocks"]["F1"]["Kf_F1"]["kf"];
							$event["F"][$value4["order"]][1]["lv"] = $value4["blocks"]["F1"]["Kf_F1"]["lv"];
							$event["F"][$value4["order"]][2]["kf"] = $value4["blocks"]["F1"]["Kf_F2"]["kf"];
							$event["F"][$value4["order"]][2]["lv"] = $value4["blocks"]["F1"]["Kf_F2"]["lv"];
						}
					}
						
						if(!empty($value3["ext"]["117"])) {
							foreach($value3["ext"]["117"]["data"] as $key4 => $value4) {
								$event["OZIN12TYME"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["OZIN12TYME"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["14"])) {
							foreach($value3["ext"]["14"]["data"] as $key4 => $value4) {
								$event["OZTEAM"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["OZTEAM"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["7"])) {
							foreach($value3["ext"]["7"]["data"] as $key4 => $value4) {
								$event["GOALS1"]["1"]["kf"] = $value4["blocks"]["YNT1"]["Y"]["kf"];
								$event["GOALS1"]["2"]["kf"] = $value4["blocks"]["YNT1"]["N"]["kf"];
								
								$event["GOALS2"]["1"]["kf"] = $value4["blocks"]["YNT2"]["Y"]["kf"];
								$event["GOALS2"]["2"]["kf"] = $value4["blocks"]["YNT2"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["123"])) {
							foreach($value3["ext"]["123"]["data"] as $key4 => $value4) {
								$event["1TIMEHAVEGOALS"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["1TIMEHAVEGOALS"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["124"])) {
							foreach($value3["ext"]["124"]["data"] as $key4 => $value4) {
								$event["2TIMEHAVEGOALS"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["2TIMEHAVEGOALS"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["15"])) {
							foreach($value3["ext"]["15"]["data"] as $key4 => $value4) {
								$event["TIMERESULTS"]["1"]["kf"] = $value4["blocks"]["X3"]["X1"]["kf"];
								$event["TIMERESULTS"]["2"]["kf"] = $value4["blocks"]["X3"]["X2"]["kf"];
								$event["TIMERESULTS"]["3"]["kf"] = $value4["blocks"]["X3"]["X3"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["9"])) {
							foreach($value3["ext"]["9"]["data"] as $key4 => $value4) {
								$event["TOTALYNCHET"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["TOTALYNCHET"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["3"])) {
							foreach($value3["ext"]["3"]["data"] as $key4 => $value4) {
								if(!empty($value4["blocks"]["IT_T1"])){
									$event["INDTOTAL1"][$value4["order"]][1]["kf"] = $value4["blocks"]["IT_T1"]["Tm"]["kf"];
									$event["INDTOTAL1"][$value4["order"]][1]["lv"] = $value4["blocks"]["IT_T1"]["Tm"]["lv"];
									$event["INDTOTAL1"][$value4["order"]][2]["kf"] = $value4["blocks"]["IT_T1"]["Tb"]["kf"];
									$event["INDTOTAL1"][$value4["order"]][2]["lv"] = $value4["blocks"]["IT_T1"]["Tb"]["lv"];
								} else {
									$event["INDTOTAL2"][$value4["order"]][1]["kf"] = $value4["blocks"]["IT_T2"]["Tm"]["kf"];
									$event["INDTOTAL2"][$value4["order"]][1]["lv"] = $value4["blocks"]["IT_T2"]["Tm"]["lv"];
									$event["INDTOTAL2"][$value4["order"]][2]["kf"] = $value4["blocks"]["IT_T2"]["Tb"]["kf"];
									$event["INDTOTAL2"][$value4["order"]][2]["lv"] = $value4["blocks"]["IT_T2"]["Tb"]["lv"];
								}
							}
						}
						
						if(!empty($value3["ext"]["137"])) {
							foreach($value3["ext"]["137"]["data"] as $key4 => $value4) {
								$event["OZIN1TYME"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["OZIN1TYME"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["138"])) {
							foreach($value3["ext"]["138"]["data"] as $key4 => $value4) {
								$event["OZIN2TYME"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["OZIN2TYME"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["130"])) {
							foreach($value3["ext"]["130"]["data"] as $key4 => $value4) {
								$event["TEAM1GOALSIN12TIME"]["1"]["kf"] = $value4["blocks"]["YNT1"]["Y"]["kf"];
								$event["TEAM1GOALSIN12TIME"]["2"]["kf"] = $value4["blocks"]["YNT1"]["N"]["kf"];
								
								$event["TEAM2GOALSIN12TIME"]["1"]["kf"] = $value4["blocks"]["YNT2"]["Y"]["kf"];
								$event["TEAM2GOALSIN12TIME"]["2"]["kf"] = $value4["blocks"]["YNT2"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["1012"])) {
							foreach($value3["ext"]["1012"]["data"] as $key4 => $value4) {
								$event["OZANDTOTALUNDER"][$value4["order"]][1]["kf"] = $value4["blocks"]["YNNum"]["Y"]["kf"];
								$event["OZANDTOTALUNDER"][$value4["order"]][1]["lv"] = $value4["blocks"]["YNNum"]["Num"];
								$event["OZANDTOTALUNDER"][$value4["order"]][2]["kf"] = $value4["blocks"]["YNNum"]["N"]["kf"];
								$event["OZANDTOTALUNDER"][$value4["order"]][2]["lv"] = $value4["blocks"]["YNNum"]["Num"];
							}
						}
						
						if(!empty($value3["ext"]["1013"])) {
							foreach($value3["ext"]["1013"]["data"] as $key4 => $value4) {
								$event["OZANDTOTALOVER"][$value4["order"]][1]["kf"] = $value4["blocks"]["YNNum"]["Y"]["kf"];
								$event["OZANDTOTALOVER"][$value4["order"]][1]["lv"] = $value4["blocks"]["YNNum"]["Num"];
								$event["OZANDTOTALOVER"][$value4["order"]][2]["kf"] = $value4["blocks"]["YNNum"]["N"]["kf"];
								$event["OZANDTOTALOVER"][$value4["order"]][2]["lv"] = $value4["blocks"]["YNNum"]["Num"];
							}
						}
						
						if(!empty($value3["ext"]["862"])) {
							foreach($value3["ext"]["862"]["data"] as $key4 => $value4) {
								$event["W1ANDOZ"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["W1ANDOZ"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
						
						if(!empty($value3["ext"]["863"])) {
							foreach($value3["ext"]["863"]["data"] as $key4 => $value4) {
								$event["W2ANDOZ"]["1"]["kf"] = $value4["blocks"]["YN"]["Y"]["kf"];
								$event["W2ANDOZ"]["2"]["kf"] = $value4["blocks"]["YN"]["N"]["kf"];
							}
						}
					
					$ev = json_encode($event);

                    if ($value3["id_ev"] != 0) {
                        $db->query("UPDATE `events` SET `bets` = '?s', `is_live` = 1, `score` = '?s', `score_all` = '?s', `time_name` = '?s', `min` = '?s', `curr_time` = '?s' WHERE `parse_id` = '?i'",
                            $ev, $value3["sc_ev"], $value3["sc_inter"], $value3["time_name"], $rrrr, $curr_time, $value3["id_ev"]);
                    }
				} else {
					$db->query("INSERT INTO `events`(`game_id`,`tournament_id`,`name_1`,`name_2`,`time_start`,`parse_id`,`score`,`score_all`,`time_name`,`min`)
					VALUES('?i','?i','?s','?s','?s','?i','?s','?s','?s','?s')",
					$game_id,$tournament_id,$value3["name_ht"],$value3["name_at"],$value3["date_ev_str"],$value3["id_ev"],$value3["sc_ev"],$value3["sc_inter"],$value3["time_name"],$value3["min"]);
				}
			}
			
			sleep(1);
			
		}
		
		if((microtime(true) - $start) >= 54) {
			exit();
		}
	}
	

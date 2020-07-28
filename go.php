<?php
	define('bk', true);
	header("Content-Type: text/html; charset=utf-8");
	@ini_set('display_errors', false);
	@ini_set('html_errors', false);

	require_once("./engine/classes/mysqli.php");
	require_once("./config.php");


	$query = $db->query('SELECT `id`,`href`,`user_id` FROM `streams` WHERE `id` = "?i" LIMIT 1', $_GET["id"]);
	$row = $query->fetch_assoc();
		
		if(!empty($row["id"])) {
			$date = date("Y-m-d");
			
			if(!empty($_GET["s1"])) {
				$s1 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["s1"]); 
				$addQuery .= " AND `s1` = '".$s1."' ";
			}
			if(!empty($_GET["s2"])) {
				$s2 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["s2"]); 
				$addQuery .= " AND `s2` = '".$s2."' ";
			}
			if(!empty($_GET["s3"])) {
				$s3 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["s3"]); 
				$addQuery .= " AND `s3` = '".$s3."' ";
			}
			if(!empty($_GET["s4"])) {
				$s4 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["s4"]); 
				$addQuery .= " AND `s4` = '".$s4."' ";
			}
			if(!empty($_GET["s5"])) {
				$s5 = preg_replace('/[^a-zA-Z0-9]/ui', '', $_GET["s5"]); 
				$addQuery .= " AND `s5` = '".$s5."' ";
			}
			
			$query2 = $db->query('SELECT `id`,`visits`,`visits_all` FROM `stats` WHERE `stream_id` = "?i" '.$addQuery.' AND `date` = "'.$date.'" LIMIT 1', $_GET["id"]);
			$row2 = $query2->fetch_assoc();
			
			if(!empty($row2["id"])) {
				$visits_all = $row2["visits_all"] + 1;
				$visits = $row2["visits"];
				
				if(empty($_COOKIE["stt"])) {
					$visits = $visits + 1;
                    $db->query('INSERT INTO `tmp_user`(`ip`, `stream_id`, `stat_id`) VALUES ("?s", "?s", "?s")', ip2long($_SERVER['REMOTE_ADDR']), $row['id'],$row2["id"]);
				}
				
				$db->query("UPDATE `stats` SET `visits` = '".$visits."', `visits_all` = '".$visits_all."' WHERE `id` = '".$row2["id"]."'");
				
				setcookie("stt", $row2["id"], time()+60*60*24*30);
				
				$addUrl = "?str=".$row["id"]."&stt=".$row2["id"];
			} else {
				$db->query('INSERT INTO `stats`(`stream_id`,`s1`,`s2`,`s3`,`s4`,`s5`,`date`,`visits`) VALUES ("?i","?s","?s","?s","?s","?s","?s","?i")', $row["id"],$s1,$s2,$s3,$s4,$s5,$date,"1");
				$insID = $db->getLastInsertId();

                $db->query('INSERT INTO `tmp_user`(`ip`, `stream_id`, `stat_id`) VALUES ("?s", "?s", "?s")', ip2long($_SERVER['REMOTE_ADDR']), $row['id'], $insID);

                setcookie("stt", $insID, time()+60*60*24*30);
				
				$addUrl = "?str=".$row["id"]."&stt=".$insID;
			}
			
			setcookie("str", $row["id"], time()+60*60*24*30);
			
			$query123 = $db->query('SELECT `mirror` FROM `users` WHERE `id` = "?i" LIMIT 1', $row["user_id"]);
			$row123 = $query123->fetch_assoc();
			
			if(!empty($row123["mirror"])) {
				header('Location: '.$row123["mirror"].'/'.$addUrl);
			} else {
				$query13 = $db->query('SELECT * FROM `info_tab` WHERE `status` = "1" LIMIT 1');
				$row13 = $query13->fetch_assoc();
				if(!empty($_GET['match_id']) && is_numeric($_GET['match_id'])) {
				    $addUrl .= "&do=event&id=".$_GET['match_id'];
                }
				header('Location: '.$row13["value"].'/'.$addUrl);
			}
		} else {
			header('Location: /');
		}
?>
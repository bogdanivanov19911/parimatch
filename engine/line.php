<?php
if (!defined('bk')) die('Hacking Attempt!');

// 1 БЛОК

$date_events = date("Y-m-d H:i:s");
$gameArray = array();

$queryGames = $db->query('SELECT * FROM `games` ORDER BY `sorting`');
$rowGames = $queryGames->fetch_assoc_array();

foreach ($rowGames as $key => $value) {
    $gameArray[$value["id"]]["name"] = $value["name"];
}

$queryTourn = $db->query('SELECT * FROM `tournaments` WHERE `name` IS NOT NULL AND `hide` = 0 AND `sorting` != 0 ORDER BY `sorting`, `name`');
$rowTourn = $queryTourn->fetch_assoc_array();

foreach ($rowTourn as $key2 => $value2) {
    $gameArray[$value2["game"]]["tournaments"][$value2["id"]]["name"] = $value2["name"];
    $gameArray[$value2["game"]]["tournaments"][$value2["id"]]["flags"] = $value2["flags"];
}

$queryEvent = $db->query('SELECT `id`,`game_id`,`tournament_id` FROM `events` WHERE `result` IS NULL AND `is_live` = 0 AND `time_start` >= "' . $date_events . '"');
$rowEvent = $queryEvent->fetch_assoc_array();

foreach ($rowEvent as $key3 => $value3) {
    if (!empty($gameArray[$value3["game_id"]]["tournaments"][$value3["tournament_id"]]["name"])) {
        $gameArray[$value3["game_id"]]["tournaments"][$value3["tournament_id"]]["events"] = $value3["id"];
    }
}

foreach ($gameArray as $key4 => $value4) {
    if (!empty($value4["tournaments"])) {
        foreach ($value4["tournaments"] as $key5 => $value5) {
            if (!empty($value5["events"])) {
                $value5["name"] = str_replace($value4["name"] . ". ", "", $value5["name"]);

                $tournament_block .= '
						<div>
							<prematch-line-championship class="prematch-block prematch-block_line">
								<a class="prematch-block__content">
									<div class="prematch-block-text" onclick="searchLeague(' . $key5 . ');">
										<h4 class="prematch-block-text__title"> ' . $value5["name"] . ' </h4>
										<div class="prematch-block-text__event"></div>
									</div>
								</a>
							</prematch-line-championship>
						</div>';
            }
        }
    }

    if (!empty($tournament_block)) {

        $game_block .= '
						  
<prematch-line-sport class="prematch-block" data-game="' . $key4 . '">
	<a class="prematch-block__content" href="#">
		<span class="prematch-block__icon">
			<i class="sporticon sporticon" data-game="' . $key4 . '"></i>
		</span>
		<div class="prematch-block-text">
			<h4 class="prematch-block-text__title"> ' . $value4["name"] . ' </h4>
			<div class="prematch-block-text__event"></div>
		</div>
	</a>
</prematch-line-sport>
						  
			<div data-parentgid="' . $key4 . '">
				<div class="topbar topbar_event topbar-event__grid" data-gamebg="' . $key4 . '">
					<div class="topbar__col topbar__col_left">
						<a class="btn topbar__left" href="/?do=line">
							<i class="icon icon-ai-angle-left icon_white"></i>
						</a>
					</div>
					<div class="topbar__col topbar__col_center">
						<span class="topbar__title">' . $value4["name"] . '</span>
					</div>
					<div class="topbar__col topbar__col_right"></div>
				</div>
				' . $tournament_block . ' 	
						<div>
							<prematch-line-championship class="prematch-block prematch-block_line">
								<a class="prematch-block__content">
									<div class="prematch-block-text"">
										<h4 class="prematch-block-text__title"> </h4>
										<div class="prematch-block-text__event"></div>
									</div>
								</a>
							</prematch-line-championship>
						</div>
			</div>';
    }

    unset($tournament_block);
}


$body = $game_block;

?>
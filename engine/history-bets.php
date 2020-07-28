<?php
if (!defined('bk')) die('Hacking Attempt!');

date_default_timezone_set("Europe/Moscow");

if ($logged) {
    $query = $db->query("SELECT COUNT(id) FROM `placed_bet` WHERE `user_id` = '?i' ORDER BY `id` DESC LIMIT 100", $user["id"]);
    $row = $query->fetch_assoc();
    $number = $row["COUNT(id)"];

    if ($number == 0) {
        $body = '<div class="error-login" style="margin-bottom: 35px;">У вас нет ставок!</div>';
    } else {
        $query11 = $db->query("SELECT *,DATE_FORMAT(date_add + INTERVAL '?s' HOUR ,'%H:%i / %d.%m') as `date_add2` FROM `placed_bet` WHERE `user_id` = '?i' ORDER BY `id` DESC", '+2', $user["id"]);
        $row11 = $query11->fetch_assoc_array();


        foreach ($row11 as $key11 => $value11) {
            unset($pressBlock);

            if ($value11["bet_status"] == 1) {
                $status = '<span style="color: #097501;">Выигрыш: ';
                $winRes = ($value11["price"] / 100) * ($value11["factor"] / 100);
                $colorStatus = "green";
            } elseif ($value11["bet_status"] == 2) {
                $status = '<span style="color: #ff2a2a;">Проигрыш: ';
                $winRes = "-" . $value11["price"] / 100;
                $colorStatus = "red";
            } elseif ($value11["bet_status"] == 4) {
                $status = '<span style="color: #7474ff;">Возврат: ';
                $winRes = $value11["price"] / 100;
                $colorStatus = "yellow";
            } elseif ($value11["bet_status"] == 0) {
                $status = '<span style="color: #498ee7;">Возможный выигрыш: ';
                $winRes = ($value11["price"] / 100) * ($value11["factor"] / 100);
                $colorStatus = "blue";
            }

            $stake = $value11["price"] / 100;


            if ($value11["type"] == 2) {
                unset($factor, $bets_array_teams, $bets_array_winner, $result_team_winner, $result_team_winner1, $result_team_winner2, $pressBlock);
                $factor = explode(",", $value11["factor"]);
                $bets_array_teams = explode(",", $value11["teams"]);
                $bets_rates = explode(",", $value11["rate"]);
                $bets_results = explode(",", $value11["bet_result"]);
                $bets_array_winner = explode(",", $value11["winner"]);

                $result_team_winner .= '<a class="table-action" style="cursor: pointer;">Экспресс</a>';

                $cic = 0;
                foreach ($bets_array_teams as $teams_winner_key => $teams_winner_value) {
                    unset($status_ordinar);
                    $factor[$cic] = $factor[$cic];
                    $result_team_winner .= '<div class="team_winner" style="display: none">' . $teams_winner_value . ' <div class="c-green">' . $bets_array_winner[$cic] . '</div> [' . $factor[$cic] / 100 . ']</div>';

                    $result_team_winner1 .= '  [' . $factor[$cic] / 100 . '] <br/>';
                    $result_team_winner2 .= $teams_winner_value . '<br/>';

                   if ($value11["bet_status"] == 1) {
                        $status_ordinar = '<span style="font-size: 12px;color: #097501;">Выигрыш</span>';
                    } elseif ($bets_results[$cic] == "REJECT") {
                        $status_ordinar = '<span style="font-size: 12px;color: #7474ff;">Возврат</span>';
                    } elseif ($value11["bet_status"] == 2) {
                        $status_ordinar = '<span style="font-size: 12px;color: #ff2a2a;">Проигрыш</span>';
                    }

                    $pressBlock .= '
									<h2 class="bet-block__head"> ' . $teams_winner_value . ' </h2>
									<div class="bet-block__row">
										<span class="bet-block__detail"> ' . $bets_array_winner[$cic] . ' </span>
										<span class="bet-block__coefficient">' . $factor[$cic] / 100 . ' </span>
										' . $status_ordinar . '
									</div>
									';

                    $cic++;
                }
                $value11["winner"] = $result_team_winner1;
                $value11["teams"] = $result_team_winner2;

                $result = reset($factor);
                for ($i = 1, $c = count($factor); $i < $c; ++$i) {
                    $result *= $factor[$i] / 100;
                }

                $value11["factor"] = round($result, 0);


                $winRes1 = ((($stake) * ($value11["factor"] / 100)) - $stake);

                if ($value11["bet_status"] == 1) {
                    $winRes = ($value11["price"] / 100) * ($value11["factor"] / 100);
                } elseif ($value11["bet_status"] == 0) {
                    $winRes = ((($stake) * ($value11["factor"] / 100)) - $stake);
                }

                $line_bets .= '
									<h2 class="bet-block__head" style="margin-left: 10px;"> Экспресс (' . $value11["factor"] / 100 . ') </h2>
									<bet-block-single class="bet-block ">
										<div class="bet-block-event bet-block-event_live">
											<div class="bet-block-event__right">
												<span class="bet-block__event-date"> ' . $value11["date_add2"] . ' </span>
											</div>
										</div>
										' . $pressBlock . '
										<div class="bet-block__row">
											<div class="bet-block__amount">Ставка: ' . $stake . ' RUB</div>
										</div>
										<div class="bet-block__row">
											<div class="bet-block__win bet-block__win_won"> ' . $status . ' ' . $winRes . ' RUB</span></div>
										</div>
										<div class="bet-block__row">
											<span class="bet-block__placed">№ ' . $value11["id"] . ' </span>
											<span class="bet-block__date">Дата ставки : ' . $value11["date_add2"] . '</span>
										</div>
									</bet-block-single>';

            } else {
                $winRes1 = ($stake) * ($value11["factor"] / 100);
                $result_team_winner = $value11["teams"] . ' - <div class="c-green">' . $value11["winner"] . '</div>';

                $line_bets .= '<bet-block-single class="bet-block ">
	<div class="bet-block-event bet-block-event_live">
		<div class="bet-block-event__right">
			<span class="bet-block__event-date"> ' . $value11["date_add2"] . ' </span>
		</div>
	</div>
	<h2 class="bet-block__head"> ' . $value11["teams"] . ' </h2>
	<div class="bet-block__row">
		<span class="bet-block__detail"> ' . $value11["winner"] . ' </span>
		<span class="bet-block__coefficient">' . $value11["factor"] / 100 . '</span>
	</div>
	<div class="bet-block__row">
		<div class="bet-block__amount">Ставка: ' . $stake . ' RUB</div>
	</div>
	<div class="bet-block__row">
		<div class="bet-block__win bet-block__win_won"> ' . $status . ' ' . $winRes . ' RUB</span></div>
	</div>
	<div class="bet-block__row">
		<span class="bet-block__placed">№ ' . $value11["id"] . ' </span>
		<span class="bet-block__date">Дата ставки : ' . $value11["date_add2"] . '</span>
	</div>
</bet-block-single>';


            }

        }


        $body = '<div class="bethistory"><inplay class="inplay">В доигрыше: <strong>' . $user["unresolved"] / 100 . ' RUB</strong></inplay></div>' . $line_bets;
    }
} else {
    $body = '<div class="error-login" style="margin-bottom: 35px;">Вы не авторизированы!</div>';
}
	
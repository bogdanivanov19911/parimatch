<?php
define('bk', true);
header("Content-Type: text/html; charset=utf-8");
@ini_set('display_errors', false);
@ini_set('html_errors', false);

header("HTTP/1.0 200 OK");

require_once("../engine/classes/function.php");
require_once("../engine/classes/mysqli.php");
require_once("../config.php");

date_default_timezone_set("UTC");

$date = date_create(date("Y-m-d H:i:s"));
$date_now = date_format($date, "Y-m-d H:i:s");

$merchant_id = MERCHANTID;
$merchant_secret = MERCHANTSECRET;
$merchant_secret2 = MERCHANTSECRET2;

function getIP()
{
    if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
    return $_SERVER['REMOTE_ADDR'];
}


if ($_REQUEST['MYKASSA_ID'] == "TEST_NOTICE") {

} else {
    $sign = md5($merchant_id . ':' . $_REQUEST['AMOUNT'] . ':' . $merchant_secret2 . ':' . $_REQUEST['MERCHANT_ORDER_ID']);

    if ($sign != $_REQUEST['SIGN']) {
        die("hacking attempt!");
    }

    $query = $db->query("SELECT `id`,`balance`,`balance_bonus`,`payment_data`, `ip`, `email` FROM `users` WHERE `id` = '?i' LIMIT 1", $_REQUEST["us_login"]);

    if ($db->getAffectedRows() == 1) {
        $row = $query->fetch_assoc();


        $queryPayment = $db->query('SELECT * FROM `payment` WHERE `id_user` = "?i" LIMIT 1', $_REQUEST["us_login"]);
        $rowPayment = $queryPayment->fetch_assoc();

        if (empty($rowPayment["id"])) {
            $query3 = $db->query("SELECT * FROM `referrals` WHERE `user_id` = '?i' OR user_ip = '?i' LIMIT 1", $_REQUEST["us_login"], ip2long($_SERVER['REMOTE_ADDR']));
            $row3 = $query3->fetch_assoc();

            if (!empty($row3['referrals_id'])) {
                $query4 = $db->query("SELECT * FROM `stats` WHERE `id` = '?i' LIMIT 1", $row3["stat_id"]);
                $row4 = $query4->fetch_assoc();

                $fdps = $row4["fdps"] + 650;
                $fdps_count = $row4["fdps_count"] + 1;

                $query5 = $db->query("SELECT `id`,`balance_agent`,`partner`, `name`, `login` FROM `users` WHERE `id` = '?i' LIMIT 1", $row3['referrals_id']);
                $row5 = $query5->fetch_assoc();

                $login = $row5['login'];
                $partnerId = $row5['id'];

                if ($row5["partner"] == 1) {
                    $balance_agent = $row5["balance_agent"] + 65000;
                    $db->query("UPDATE `users` SET `balance_agent` = '?i' WHERE `id` = '?i'", $balance_agent, $row3['referrals_id']);
                }

                $db->query("UPDATE `stats` SET `fdps` = '?i', `fdps_count` = '?i' WHERE `id` = '?i'", $fdps, $fdps_count, $row4["id"]);

                $query6 = $db->query("SELECT `id`,`user_id`,`fdep_pb`,`postback`,`source_id` FROM `streams` WHERE `id` = '?i' LIMIT 1", $row4["stream_id"]);
                $row6 = $query6->fetch_assoc();

                if ($row6["fdep_pb"] == 1) {
                    $url = $row6["postback"];
                    $url = str_replace("{id}", $row2['id'], $url);
                    $url = str_replace("{status}", "deposit", $url);
                    $url = str_replace("{date}", strtotime(date("Y-m-d H:i:s")), $url);
                    $url = str_replace("{sub1}", $row4["s1"], $url);
                    $url = str_replace("{sub2}", $row4["s2"], $url);
                    $url = str_replace("{sub3}", $row4["s3"], $url);
                    $url = str_replace("{sub4}", $row4["s4"], $url);
                    $url = str_replace("{sub5}", $row4["s5"], $url);
                    $url = str_replace("{stream}", $row6['id'], $url);
                    $url = str_replace("{source}", $row6['source_id'], $url);
                    $url = str_replace("{fdp}", $_REQUEST['AMOUNT'], $url);

                    file_get_contents($url);
                }
            }
        } else {
            $query3 = $db->query("SELECT * FROM `referrals` WHERE `user_id` = '?i' LIMIT 1", $_REQUEST["us_login"]);
            $row3 = $query3->fetch_assoc();

            $query5 = $db->query("SELECT  `id`, `login` FROM `users` WHERE `id` = '?i' LIMIT 1", $row3['referrals_id']);
            $row5 = $query5->fetch_assoc();

            $login = $row5['login'];
            $partnerId = $row5['id'];
        }


        if (empty($rowPayment["id"])) {
            $balance_bonus = $row['balance_bonus'] + ($_REQUEST['AMOUNT'] * 300);

           // $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
            //    $_REQUEST["us_login"], $_REQUEST['AMOUNT'] * 3, "���������� ����� ��������. �����: +" . $_REQUEST['AMOUNT'] * 3, date("Y-m-d H:i:s"), 1);

            $payment_data = json_encode(array("count" => 1, "summ" => $_REQUEST['AMOUNT']));

            $db->query("UPDATE `users` SET `balance_bonus` = '?i', `payment_data` = '?s'  WHERE `id` = '?i'", $balance_bonus, $payment_data, $_REQUEST["us_login"]);
        } else {
            $dp_inf = json_decode($row['payment_data'], true);

            if ($dp_inf['summ'] >= $_REQUEST['AMOUNT']) {
                $payment_data = json_encode(array("count" => $dp_inf['count'] + 1, "summ" => $_REQUEST['AMOUNT']));
            }
        }

        if (empty($row3["stream_id"])) {
            $db->query('INSERT INTO `payment`(`id_user`,`price`,`date_pay`,`status`) VALUES ("?i","?s","?s","?i")', $_REQUEST["us_login"], $_REQUEST['AMOUNT'], $date_now, "1");
        } else {
            $db->query('INSERT INTO `payment`(`id_user`,`price`,`date_pay`,`status`,`stream_id`,`stat_id`) VALUES ("?i","?s","?s","?i","?i","?i")', $_REQUEST["us_login"], $_REQUEST['AMOUNT'], $date_now, "1", $row3["stream_id"], $row3["stat_id"]);
        }

        $depQuery = $db->query('SELECT COUNT(*) as count FROM `payment` WHERE `id_user` = "?i"', $_REQUEST["us_login"]);
        $depCount = $depQuery->fetch_assoc()['count'];
        $refMoney = 0.35*$_REQUEST['AMOUNT'];
        if ($depCount == 1) {
            $bread = "🥖";
            $balance = 100000;
            $refMoney = 0.5*$_REQUEST['AMOUNT'];
        }
        elseif ($depCount > 1 and $depCount < 10) {
            $bread = str_repeat("🎩",$depCount);
        }

        else $bread = "👑".str_repeat("🎩", $depCount - 10);

        $balance += $row['balance'] + 2*($_REQUEST['AMOUNT'] * 100); //временный х2

        $db->query("INSERT INTO `logs_stat`(`user_id`,`amount`,`comment`,`date`,`status`) VALUES('?i','?s','?s','?s','?i')",
            $_REQUEST["us_login"], $_REQUEST['AMOUNT'], "Пополнение счета: +" . $_REQUEST['AMOUNT'], date("Y-m-d H:i:s"), 1);

        $db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $balance, $_REQUEST["us_login"]);

        $startToday = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $endToday = date("Y-m-d" ,mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        if (!empty($login) && !empty($partnerId)) {
            $queryUsersList = $db->query('SELECT `user_id` FROM `referrals` WHERE `referrals_id` = "?i"', $partnerId);
            $rowUsersList = $queryUsersList->fetch_assoc_array();

            foreach($rowUsersList as $keyUsersList => $valueUsersList) {
                $usersListAll .= $valueUsersList["user_id"].",";
            }
            $usersListAll = chop($usersListAll, ",");
            $querySum = $db->query('SELECT SUM(price) as sum FROM `payment` WHERE `id_user` IN ('.$usersListAll.') AND `date_pay` >= "'.$startToday.' 00:00:01" AND `date_pay` <= "'.$endToday.' 23:59:59" AND (`status` = 1 OR `status` = 5) AND `hide` IS NULL');
            $rowSum = $querySum->fetch_assoc();
            $SumPartner = $rowSum['sum'];


//            $message = "PumbaBet Шляпик " .$login. " с id " . $_REQUEST['us_login'] . " лишился " . $_REQUEST['AMOUNT'] . " рублей\nCумма депов за день от $login: $SumPartner";
            $message = " $bread \n$login \nid:". $_REQUEST['us_login'] . "\norder:". $_REQUEST['intid'] . "\nemail: ".$row['email']."\nip: ".long2ip($row['ip'])."\nDep: ". $_REQUEST['AMOUNT'] . " / $refMoney\nAmount from $login: $SumPartner";
        } else {
//            $message = 'PumbaBet Шляпик с id ' . $_REQUEST['us_login'] . ' лишился ' . $_REQUEST['AMOUNT'] . ' рублей';
            $message = "✅\n$bread \nid:". $_REQUEST['us_login'] . "\norder:". $_REQUEST['intid'] ."\nemail: ".$row['email']."\nip: ".long2ip($row['ip'])."\nDep: ". $_REQUEST['AMOUNT'];
        }
        $bot = '1091115901:AAGSS8xt6-n25LGgEHg9TVDZVwK1jZRjKdQ';

        $message = urlencode($message);

//        if($login == 'burovayanarbew') {
//            file_get_contents('https://api.telegram.org/bot970218855:AAEgC3k0wSvCmectFT6sZE1ffvsIq3n-hD8/sendMessage?chat_id=-1001257019389&text=' . $message);
//        }

        if($login == 'Tiger88') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001413930523&text=' . $message);
        }
        if($login == 'BetMester') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001203988046&text=' . $message);
        }
        if($login == 'Kapper7Pro') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001455410406&text=' . $message);
        }
        if($login == 'CashTurbo') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001169046708&text=' . $message);
        }
        if($login == 'GoldWin') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001413554134&text=' . $message);
        }
        if($login == 'KillerXXX') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001231854684&text=' . $message);
        }
        if($login == 'BetBetovich') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001152242981&text=' . $message);
        }
        if($login == 'Phantom01') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001296117274&text=' . $message);
        }
        if($login == 'EasyMoney') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001498502173&text=' . $message);
        }
        if($login == 'WealthLife') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001301273741&text=' . $message);
        }
        if($login == 'Nord') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001373473625&text=' . $message);
        }
        if ($login == 'Skat') {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001326916187&text=' . $message);
        }
        //https://api.telegram.org/bot1091115901:AAGSS8xt6-n25LGgEHg9TVDZVwK1jZRjKdQ/sendMessage?chat_id=-1001326916187&text=asdfasdf

        $queryD = $db->query("SELECT SUM(price) as sum FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND `date_pay` >= '".$startToday." 00:00:01' AND `date_pay` <= '".$endToday." 23:59:59'");
        $rowD = $queryD->fetch_assoc();
        $DepAllDay = $rowD['sum'];

        $queryD = $db->query("SELECT SUM(price) as sum FROM `payment` WHERE (`status` = 1 OR `status` = 5) AND `hide` IS NULL AND (`stream_id` IS NULL AND `stat_id` IS NULL) AND `date_pay` >= '".$startToday." 00:00:01' AND `date_pay` <= '".$endToday." 23:59:59'");
        $rowD = $queryD->fetch_assoc();
        $DepAllDayWithoutRef = $rowD['sum'];
        $message = urldecode($message);
        $message .= "\n --------------------------------- \nDay: $DepAllDay \nWithout ref: $DepAllDayWithoutRef";
        $message = urlencode($message);

        file_get_contents('https://api.telegram.org/bot'.$bot.'/sendMessage?chat_id=-1001181403902&text=' . $message); // shlyapersu
        if ($_REQUEST['AMOUNT'] >= 5000) {
            file_get_contents('https://api.telegram.org/bot'.$bot.'/sendSticker?chat_id=-1001181403902&sticker=CAACAgIAAxkBAAEEt2pey7VmAbJfZC9MsAv9FM8benG7-wAC1QADnNbnCsbMGTvvZlJeGQQ');
        }
    } else {

    }
}

die('YES');

?>
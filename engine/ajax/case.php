<?php
//open case

header("Content-Type: text/html; charset=utf-8");
define('bk', true);
@ini_set('display_errors', false);
@ini_set('html_errors', false);
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/function.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/templater.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/engine/classes/mysqli.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
$ip = ip2long($_SERVER['REMOTE_ADDR']);

date_default_timezone_set("UTC");

function run($case, $items)
{
    $itemsPrice = array();
    $rand = mt_rand(1, 100);
    $k = 0;
    if ($rand <= $case['bad_procent']) {
        foreach ($items as $i) {
            if ($i['price'] <= $case['price']) {
                $itemsPrice[] = $i;
                $k++;
            }
        }
        $rands = mt_rand(0, $k - 1);
        $item = $itemsPrice[$rands];
        return $item;
    } else {
        foreach ($items as $i) {
            if ($i['price'] >= $case['price']) {
                $itemsPrice[] = $i;
                $k++;
            }
        }
        $rands = mt_rand(0, $k - 1);
        $item = $itemsPrice[$rands];
        return $item;
    }
}

$db->setTypeMode(Database_Mysql::MODE_TRANSFORM);

if (isset($_COOKIE['hash']) and isset($_COOKIE['id'])) {
    $query = $db->query('SELECT `id`,`login`,`hash`,`email`,`name`,`surname`,`balance`,`balance_bonus`,`bonus_percent`,INET_NTOA(ip),`status` FROM `users` WHERE `hash` = "?s" AND `id` = "?i" LIMIT 1', $_COOKIE['hash'], $_COOKIE['id']);
    $user = $query->fetch_assoc();

    if ($db->getAffectedRows() == 1) {
        if ($user["status"] == 1) {
            $logged = FALSE;
        } else {
            $logged = TRUE;
        }
    } else {
        $logged = FALSE;
    }
} else {
    $logged = FALSE;
}

if (!$logged) {
    echo json_encode(array('status' => 0, 'error' => 'Вы не авторизованы!'));
    exit;
}

$query = $db->query('SELECT * FROM `case` WHERE id = ' . intval($_GET['gameId']) . ' LIMIT 1');
$case = $query->fetch_assoc();

if (!$case) {
    echo json_encode(array('status' => 0, 'error' => 'Такого кейса не существует'));
    exit;
}

$query = $db->query('SELECT * FROM items WHERE cases_id = ' . intval($case['id']));
$items = $query->fetch_assoc_array();

if (!$items) {
    echo json_encode(array('status' => 0, 'error' => 'Такого кейса не существует'));
    exit;
}

if (($case['price'] * 100) > $user['balance']) {
    echo json_encode(array('status' => 0, 'error' => 'Не достаточно денег на балансе!'));
    exit;
}


$item = run($case, $items);
$user['balance'] = $user['balance'] - $case['price'] * 100 + $item['price'] * 100;
//update user balance
$db->query("UPDATE `users` SET `balance` = '?i' WHERE `id` = '?i'", $user["balance"], $user["id"]);

//update case stats
$won = $item['price'] > $case['price'] ? $item['price'] - $case['price'] : 0;
$lost = $item['price'] < $case['price'] ? $case['price'] - $item['price'] : 0;
$db->query("INSERT INTO case_stats (`user_id`, `win`, `lost`, `created_at`) VALUES (?i, ?i, ?i, ?i)", $user["id"], $won, $lost, time());

//update partner balance
$query = $db->query('SELECT * FROM referrals INNER JOIN users ON users.id = referrals.referrals_id WHERE referrals.user_id = ?i', $user["id"]);
$referral = $query->fetch_assoc_array();
if ($referral && isset($referral[0])) {
    $referral = $referral[0];
    $bonus = ($referral["balance_agent"] / 100 + ($lost - $won) *  $referral["revshare"] / 100) * 100;
    $db->query("UPDATE `users` SET `balance_agent` = '?i' WHERE `id` = '?i'", $bonus, $referral["id"]);
}

//send answer
echo json_encode(array(
    'status' => 1,
    'data' => array(
        'gift' => $item['id'],
        'win_sum' => $item['price'],
        'text' => $item['price'] . ' рублей',
        'photo' => $item['img'],
        'balance' => ($user['balance'] / 100) . ' ₽'
    )
));
exit;
<?php
if (!defined('bk')) die('Hacking Attempt!');

date_default_timezone_set("Europe/Moscow");
$caseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($caseId) {
    $query = $db->query("SELECT * FROM `case` WHERE type = 'money' AND id = $caseId");
    $case = $query->fetch_assoc_array();
    if (!($case && isset($case[0]) && !empty($case[0]))) {
        header("Location: /");
        exit;
    }
    $query = $db->query("SELECT * FROM `items` WHERE cases_id = ?i", $caseId);
    $items = $query->fetch_assoc_array();;
    if (!($items && isset($items[0]) && !empty($items[0]))) {
        header("Location: /");
        exit;
    }

    $case = $case[0];
    $win = round(abs(sin($case['id'])) * 999 * date('z') + date('H') + date('i')) + 2000000;
    $tpl = new template('template/case.tpl');
    $tpl->set('{img}', $case['img']);
    $tpl->set('{price}', $case['price']);
    $tpl->set('{price_max}', $case['price_max']);
    $tpl->set('{price_min}', $case['price_min']);
    $tpl->set('{id}', $case['id']);
    $tpl->set('{x10}', $case['x10']);
    $tpl->set('{x20}', $case['x20']);
    $tpl->set('{x30}', $case['x30']);
    $tpl->set('{win}', $win);

    $spinImages = '';
    $historyImages = '';
    foreach ($items as $item) {
        $spinImages .= sprintf('<img src="%s" alt="%s рублей" id="gift-id-%d">', $item['img'], $item['price'], $item['id']);
        $historyImages .= sprintf('<div class="history-case">
            <div class="coin gold">
                <img src="%s" alt="%s рубль">
            </div>
        </div>', $item['img'], $item['price']);
    }
    $tpl->set('{spin_images}', $spinImages);
    $tpl->set('{history_images}', $historyImages);

    $body = $tpl->parse() . "\n\n";
} else {
    $query = $db->query("SELECT * FROM `case` WHERE type = 'money'");
    $cases = $query->fetch_assoc_array();

    $casesHtml = '';
    foreach ($cases as $case) {
        $win = round(abs(sin($case['id'])) * 999 * date('z') + date('H') + date('i')) + 2000000;

        $tpl = new template('template/case_small.tpl');
        $tpl->set('{img}', $case['img']);
        $tpl->set('{price}', $case['price']);
        $tpl->set('{price_max}', $case['price_max']);
        $tpl->set('{price_min}', $case['price_min']);
        $tpl->set('{id}', $case['id']);
        $tpl->set('{win}', $win);
        $casesHtml .= $tpl->parse();
    }

    $tpl = new template('template/cases.tpl');
    $tpl->set('{cases}', $casesHtml);

    $body = $tpl->parse() . "\n\n";
}
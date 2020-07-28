<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	if($logged) {
		function GetKey($lang = 10) {
			$key = '';
			$arr = array('1','2','3','4','5','6','7','8','9','0');
			
			for($i=0; $i<$lang; $i++) {
				$index = rand(0,35);
				$key .= $arr[$index];
			}
			return $key;
		}
        $depQuery = $db->query('SELECT COUNT(*) as count FROM `payment` WHERE `id_user` = "?i"', $user['id']);
        $depCount = $depQuery->fetch_assoc()['count'];
        $coeff = 1;
        if ($depCount >= 1 && $_POST['oa'] < 4000) {
            $_POST['oa'] = $_POST['oa'] * 1.1;
            $coeff = 1.1;
        }
		if(!empty($_POST['oa']) && $_POST['oa'] >= 1500 * $coeff) {
			$_POST['oa']=round($_POST['oa'], 2);
			$sign = md5(MERCHANTID .':'.$_POST['oa'].':'. MERCHANTSECRET .':'.$_POST['o']);
			
			header('Location: https://www.free-kassa.ru/merchant/cash.php?m='. MERCHANTID .'&oa='.$_POST['oa'].'&o='.$_POST['o'].'&s='.$sign.'&lang=en&us_login='.$user["id"].'&pay=Continue');
		} elseif(!empty($_GET['oa']) && $_POST >=1500 * $coeff ) {
			$order = "9999". GetKey(32);
			$_GET['oa']=round($_GET['oa'], 2);
			$sign = md5(MERCHANTID .':'.$_GET['oa'].':'. MERCHANTSECRET .':'.$order);
			
			header('Location: https://www.free-kassa.ru/merchant/cash.php?m='. MERCHANTID .'&oa='.$_GET['oa'].'&o='.$order.'&s='.$sign.'&lang=en&us_login='.$user["id"].'&pay=Continue');
		} else {
			$tpl = new template('template/deposit.tpl');
			if(isset($_GET["am"])){
				$tpl->set('{amount}', $_GET["am"]);
			} else {
				$tpl->set('{amount}', 1000);
			}
			if((!empty($_POST['oa']) && $_POST['oa'] < 1500 * $coeff) || (!empty($_GET['oa']) && $_GET['oa'] < 1500 * $coeff)) {
                $tpl->set('{error}', '<div class="error-login" style="margin-bottom: 35px;">Минимальная сумма пополнения 1500</div>');
            } else {
                $tpl->set('{error}', "");
            }
			$tpl->set('{user}', $user["id"]);
			$tpl->set('{pay_id}', GetKey(10));
			$body = $tpl->parse();
		}
	} else {
			$body = '<div class="error-login" style="margin-bottom: 35px;">Зарегистрируйтесь, чтобы иметь возможность пополнить баланс!</div>';
	}
	
?>
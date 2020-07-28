<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	class BK {
		static function factor($factor, $type = "echo") {
			if($type == "echo") {
				$factor = $factor / 100;
			} else {
				$factor = $factor * 100;
			}
			
			if($factor == 0) {
				$factor = "0.00";
			}
			
			return $factor;
		}
	}
	
	function format_coef($factor) {
		if($factor<=1) {
			$factor = 1;
		}
		
		if($_COOKIE['format'] == 1) {
			return round($factor,2);
		} elseif($_COOKIE['format'] == 2) {
			if($factor < 2) {
				return round((-100)/($factor - 1));
			} else {
				return round(($factor - 1) * 100);
			}
		}
	}
	
	function format_coef_reverse($factor) {
		if($_COOKIE['format'] == 1) {
			return $factor;
		} elseif($_COOKIE['format'] == 2) {
			if($factor > 0) {
				return ($factor / 100) + 1;
			} else {
				return (100 / $factor) + 1;
			}
		}
	}
?>
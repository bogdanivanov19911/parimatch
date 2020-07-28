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
?>
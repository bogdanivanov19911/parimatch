<?php
	if(!defined('bk')) die('Hacking Attempt!');
	
	class template {
		private $file = '';
		private $vars = array();
		var $template = false;

		function __construct($file) {
			$this->file = $file;
			if(empty($this->file) or !file_exists($this->file)){
				exit('Файл "'.$file.'" не найден!');
			}
			$this->template = file_get_contents($this->file);
			return true;
		}
		
		function set($key,$var) {
			$this->vars[$key] = $var;
		}
		
		
		function print_module($match=array()) {
			$module = $match[2];
			$content = $match[3];
			global $do;
			
			$content= str_replace('\"','"', $content);
			if(empty($do)) {$do = "main";}
			$module = explode("|", $module);
			if(is_array($matches = explode("[else]",$content))) {
				$content = $matches[0];
				$else = $matches[1];
			}
			
			foreach($module as $active) {
				if($active == $do) {
					$text = $content;
				}
			}
			
			if(empty($text)) {
				return $else;
			} else {
				return $text;
			}
		}
		
		function logged($content=array()) {
			global $user_login;
			
			if(is_array($matches = explode("[else]",$content[2]))) {
				$content = str_replace("\'", "'", $matches[0]);
				$else = str_replace("\'", "'", $matches[1]);
			}
			if(!empty($user_login)) {
				return $content;
			} else {
				return $else;
			}
		}
		
		function parse() {
			if (strpos ( $this->template, "[logged" ) !== false) {
				$this->template = preg_replace_callback ("#\\[(logged)\\](.*?)\\[/logged\\]#is" , array( &$this, 'logged') , $this->template );
			}
			
			if (strpos ( $this->template, "[print=" ) !== false) {
				$this->template = preg_replace_callback ( "#\\[(print)=(.+?)\\](.*?)\\[/print\\]#is", array( &$this, 'print_module'), $this->template );
			}
			
			if(count($this->vars) < 1) return false;
			foreach($this->vars as $find => $replace) {
				$this->template = str_replace($find, $replace, $this->template);
			}
			return $this->template;
		}
	}
?>
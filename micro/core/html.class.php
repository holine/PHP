<?php
class Html{
	private $var = array();
	function display(){
		
	}
	function assign($key, $value){
		$this->var[$key] = $value;
	}
	function fetch(){
		extract($this->var);
	}
}
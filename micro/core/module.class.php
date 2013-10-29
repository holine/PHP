<?php
class Module{
	public $html;
	public $cfg;
	function __construct($cfg){
		$this->cfg = $cfg;
		$this->html = new Html($cfg);
	}
	final public function __call($name, $parame){
		return call_user_func_array(include $name.'.method.php', $parame);
	}
}
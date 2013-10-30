<?php
abstract class ModuleAbstract{
	public $html;
	public $cfg;
	function __construct($cfg){
		$this->cfg = $cfg;
		$this->html = new Html($cfg);
		var_export($this);
	}
	final public function __call($name, $parame){
		return call_user_func_array(include $name.'.method.php', $parame);
	}
}
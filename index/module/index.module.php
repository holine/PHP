<?php
class IndexModule extends Module{
	public $models = 'login,register';
	public $vars = 1;
	function home(){
		$this->html->assign('hello', 'world');
		$this->html->display();
	}
}
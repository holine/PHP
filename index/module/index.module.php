<?php
class IndexModule extends Index {
	public $models = 'login,register';
	public $vars = 1;
	function home(){
		$this->html->display();
	}
}
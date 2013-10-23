<?php
class IndexModule extends Index {
	public $models = 'login,register';
	function home(){
		$this->login->hello();
		$this->register->welcome();
	}
}
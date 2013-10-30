<?php
class Html {
	private $var = array ();
	private $cfg = array ();
	function __construct($cfg) {
		$this->cfg = $cfg;
	}
	function display($view = NULL, $layout = NULL, $cacheid = NULL) {
		echo $this->fetch ( $view, $layout, $cacheid );
	}
	function assign($key, $value) {
		$this->var [$key] = $value;
	}
	function fetch($view = NULL, $layout = NULL, $cacheid = NULL) {
		$view = is_null ( $view ) ? $_REQUEST [ACTION] : preg_replace ( '/\.|\\|\//is', '', $view );
		extract ( $this->var );
		ob_start ();
		include $this->cfg ['micro'] ['html'] ['template'] . DIRECTORY_SEPARATOR . $_REQUEST [MODULE] . DIRECTORY_SEPARATOR . $_REQUEST [ACTION] . '.php';
		$buffer = ob_get_contents ();
		ob_end_clean ();
		return $this->filter_space ( $buffer );
	}
	private function filter_space($buffer) {
		$buffer = preg_replace ( '/\a|\t|\r|\v|\f|\n|\e/is', '  ', $buffer );
		$buffer = preg_replace_callback ( '/(?<=\>)([^<>]*)(?=\<)/is', function ($match) {
			return preg_replace ( '/\s/is', '', $match [1] );
		}, $buffer );
		$buffer = preg_replace ( '/\s{2,}/is', ' ', $buffer );
		return $buffer;
	}
}
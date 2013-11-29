<?php
class Html {
	private $var = array ();
	private $cfg = array ();
	public $compile;
	public $cache;
	private $cache_path = '/usr/share/nginx/html/Holine/index/cache';
	function __construct($cfg) {
		$this->cfg = $cfg;
		$this->compile = new HtmlCompile ();
		$this->var = new stdClass ();
		$this->cache = new stdClass ();
	}
	function display($view = NULL, $layout = NULL, $cacheid = NULL) {
		echo $this->fetch ( $view, $layout, $cacheid );
	}
	function assign($key, $value) {
		$this->var->$key = $value;
	}
	function fetch($view = NULL, $layout = NULL, $cacheid = NULL, $compileid = NULL) {
		$view = is_null ( $view ) ? $_REQUEST [ACTION] : preg_replace ( '/\.|\\|\//is', '', $view );
		$cache_path = $this->cache_path ( $view, $layout, $cacheid, $compileid );
		if (! $this->is_cached ( $cache_path )) {
			// $this->cache_path
		}
	}
	private function filter_space($buffer) {
		$buffer = preg_replace ( '/\a|\t|\r|\v|\f|\n|\e/is', '  ', $buffer );
		$buffer = preg_replace_callback ( '/(?<=\>)([^<>]*)(?=\<)/is', function ($match) {
			return preg_replace ( '/\s/is', '', $match [1] );
		}, $buffer );
		$buffer = preg_replace ( '/\s{2,}/is', ' ', $buffer );
		return $buffer;
	}
	private function ob_get_contents() {
		ob_start ();
		include $this->compile->compile ( $this->cfg ['micro'] ['html'] ['template'] . DIRECTORY_SEPARATOR . $_REQUEST [MODULE] . DIRECTORY_SEPARATOR . $_REQUEST [ACTION] . '.php' );
		$buffer = ob_get_contents ();
		ob_end_clean ();
		return $this->filter_space ( $buffer );
	}
}
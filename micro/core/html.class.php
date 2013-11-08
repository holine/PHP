<?php
class Html {
	private $var = array ();
	private $cfg = array ();
	public $compile;
	private $cache_path = '/usr/share/nginx/html/Holine/index/cache';
	function __construct($cfg) {
		$this->cfg = $cfg;
		$this->compile = new HtmlCompile ();
		$this->var = new stdClass ();
	}
	function display($view = NULL, $layout = NULL, $cacheid = NULL) {
		echo $this->fetch ( $view, $layout, $cacheid );
	}
	function assign($key, $value) {
		$this->var->$key = $value;
	}
	function fetch($view = NULL, $layout = NULL, $cacheid = NULL) {
		$view = is_null ( $view ) ? $_REQUEST [ACTION] : preg_replace ( '/\.|\\|\//is', '', $view );
		if (!$this->is_cached($view, $layout, $cacheid, $compileid)) {
			//$this->cache_path
		}
	}
	private function is_cached($view = NULL, $layout = NULL, $cacheid = NULL, $compileid= NULL) {
		$cache_path = $this->cache_path($view, $layout, $cacheid, $compileid);
		if (!is_file($cache_path)) {
			$this->write($path, $this->ob_get_contents());
		}
		$this->read($path);
	}
	private function cache_path($view = NULL, $layout = NULL, $cacheid = NULL) {
		$cache = md5 ( $view . '@@' . $layout . '@@' . $cacheid . '@@' . $compileid );
		return $this->cache_path . substr ( $cache, 0, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 2, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 4, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 6 ) . '.html';
	}
	private function filter_space($buffer) {
		$buffer = preg_replace ( '/\a|\t|\r|\v|\f|\n|\e/is', '  ', $buffer );
		$buffer = preg_replace_callback ( '/(?<=\>)([^<>]*)(?=\<)/is', function ($match) {
			return preg_replace ( '/\s/is', '', $match [1] );
		}, $buffer );
		$buffer = preg_replace ( '/\s{2,}/is', ' ', $buffer );
		return $buffer;
	}
	private function file_get_contents($path) {
		$cache = $this->cfg ['micro'] ['html'] ['cache'] . DIRECTORY_SEPARATOR . md5 ( $path ) . '.php';
		file_put_contents ( $cache, file_get_contents ( $path ) );
		return $cache;
	}
	private function ob_get_contents(){
		ob_start ();
		include $this->compile->compile ( $this->cfg ['micro'] ['html'] ['template'] . DIRECTORY_SEPARATOR . $_REQUEST [MODULE] . DIRECTORY_SEPARATOR . $_REQUEST [ACTION] . '.php' );
		$buffer = ob_get_contents ();
		ob_end_clean ();
		return $this->filter_space ( $buffer );
	}
	private function write($path, $contents){
		$dir = dirname($path);
		if (!is_dir($dir)) {
			mkdir($dir, 0777, TRUE);
		}
		file_put_contents($path, $contents);
	}
	private function read($path){
		return file_get_contents($path);
	}
}
<?php
spl_autoload_register ( function ($class) {
	static $init = FALSE;
	if ($init === FALSE) {
		set_include_path ( get_include_path () . PATH_SEPARATOR . __DIR__ . DIRECTORY_SEPARATOR . 'core' );
		$init = TRUE;
	}
	if (strrpos ( $class, 'Module', - 1 )) {
		include strtolower ( substr ( $class, 0, - 6 ) ) . '.module.php';
	} else if (strrpos ( $class, 'Model', - 1 )) {
		include strtolower ( substr ( $class, 0, - 5 ) ) . '.model.php';
	} else if (strrpos ( $class, 'Abstract', - 1 )) {
		include strtolower ( substr ( $class, 0, -8 ) ) . '.abstract.php';
	} else {
		include strtolower ( $class ) . '.class.php';
	}
} );
class micro extends SingleAbstract {
	private $cfg;
	protected function __construct($path = '', $cfg = array()) {
		$this->cfg = ! empty ( $path ) && is_file ( $path ) ? array_merge_recursive ( include $path, $cfg ) : $cfg;
		
		if (isset ( $this->cfg ['system'] )) {
			foreach ( $this->cfg ['system'] as $key => $value ) {
				ini_set ( $key, $value );
			}
		}
		if (isset ( $this->cfg ['header'] )) {
			$ext = pathinfo ( $_SERVER ['PHP_SELF'], PATHINFO_EXTENSION );
			header ( "Content-type: {$this->cfg['header']['type'][$ext]}; charset={$this->cfg['header']['charset']}" );
		}
		define ( 'MODULE', $this->cfg ['micro'] ['module'] );
		define ( 'ACTION', $this->cfg ['micro'] ['action'] );
		set_include_path ( get_include_path () . PATH_SEPARATOR . $this->cfg ['micro'] ['dir'] ['module'] . PATH_SEPARATOR . $this->cfg ['micro'] ['dir'] ['model'] . PATH_SEPARATOR . $this->cfg ['micro'] ['dir'] ['method']);
		
		if (empty ( $_REQUEST [MODULE] )) {
			$_REQUEST [MODULE] = $this->cfg ['micro'] ['default'] ['module'];
		}
		if (empty ( $_REQUEST [ACTION] )) {
			$_REQUEST [ACTION] = $this->cfg ['micro'] ['default'] ['action'];
		}
	}
	public function route() {
		$m = $_REQUEST [MODULE] . 'Module';
		$m = new $m ( $this->cfg );
		$m->cfg = $this->cfg;
		$m->html = new html($this->cfg);
		foreach ( explode ( ',', $m->models ) as $model ) {
			$modelName = "{$model}Model";
			$m->$model = new $modelName ();
		}
		$m->$_REQUEST [ACTION] ();
	}
}
<?php
abstract class SingleAbstract {
	private static $instance;
	protected function __construct() {
	}
	public final static function init($cfg = array()) {
		static $instance;
		return $instance ? $instance : $instance = new static ($cfg);
	}
}
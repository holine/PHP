<?php
class HtmlCompile {
	private $force_compile = TRUE;
	private $cache_path = '/usr/share/nginx/html/Holine/index/compile';
	private function is_compile($path = '/', $compile_path = '/') {
		return ! ($this->force_compile || (is_file ( $path ) && is_file ( $compile_path ) && filemtime ( $path ) > filemtime ( $compile_path )));
	}
	public function compile($path, $version = 'version 1.0') {
		$compile_path = $this->compile_path ( $path, $version );
		if (! $this->is_compile ( $path, $compile_path )) {
			$source = file_get_contents ( $path );
			$source = $this->complie_include ( $source, dirname ( $path ), $version );
			$source = $this->compile_echo ( $source, dirname ( $path ), $version );
			$this->write ( $compile_path, $source );
		}
		return $compile_path;
	}
	
	/**
	 * 生成编译后的文件名
	 *
	 * @param string $path
	 *        	模板绝对路径
	 * @param string $compile
	 *        	模板的编译版本
	 * @return string 编译后的文件名
	 */
	private function compile_path($path = '/', $compile = 'version 1.0') {
		return $this->cache_path . DIRECTORY_SEPARATOR . md5 ( $path . '@@' . $compile ) . '.php';
	}
	
	/**
	 * 编译模板中 include 语法
	 *
	 * @param string $path        	
	 * @param string $absolute        	
	 * @param string $version        	
	 */
	private function complie_include($source = '', $absolute = '/', $version = 'version 1.0') {
		return preg_replace_callback ( '/(\{include\s*([^} ]*)\s*})/is', function ($match) use($absolute, $version) {
			return '<?php include "' . $this->compile ( $absolute . DIRECTORY_SEPARATOR . $match [2], $version ) . '"; ?>';
		}, $source );
	}
	private function compile_echo($source = '', $absolute = '/', $version = 'version 1.0') {
		return preg_replace_callback ( '/(\{\$([_a-z][_a-z0-9]*)})/is', function ($match) use($absolute, $version) {
			return '<?php echo $this->var->' . $match [2] . '; ?>';
		}, $source );
	}
	private function write($path, $contents) {
		$dir = dirname ( $path );
		if (! is_dir ( $dir )) {
			mkdir ( $dir, 0777, TRUE );
		}
		file_put_contents ( $path, $contents );
	}
}
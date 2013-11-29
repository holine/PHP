<?php
class HtmlCache{
	public $expires = 0;
	/**
	 * 读取文件内容
	 *
	 * @param string $path
	 *        	文件路径
	 * @param string $cache
	 *        	是否缓存文件，默认为缓存文件
	 * @return string 返回读取到的内容
	 */
	private function read($path, $cache = TRUE) {
		return $cache ? file_get_contents ( $path, NULL, NULL, floatval ( file_get_contents ( $path, NULL, NULL, 10, 10 ) ) ) : file_get_contents ( $path );
	}
	/**
	 * 写入内容到硬盘
	 *
	 * @param string $path
	 *        	文件路径
	 * @param string $contents
	 *        	待写入的内容
	 * @param array $param
	 *        	如果是不是输出的缓存文件，请使用非数组的数据类型
	 * @return number 返回写入的字符个数，或者失败后返回 false
	 */
	private function write($path, $contents, $param = array()) {
		$dir = dirname ( $path );
		if (! is_dir ( $dir )) {
			mkdir ( $dir, 0777, TRUE );
		}
		if (is_array ( $param )) {
			$contents = json_encode ( $param ) . $contents;
			$contents = ($this->cache->expires + time ()) . sprintf ( '%010d', 20 + strlen ( $contents ) ) . $contents;
		}
		return file_put_contents ( $path, $contents );
	}
	private function is_cached($path) {
		return is_file ( $path ) ? (time () < floatval ( file_get_contents ( $path, NULL, NULL, 0, 10 ) ) ? TRUE : FALSE) : FALSE;
	}
	private function cache_path($view = NULL, $layout = NULL, $cacheid = NULL) {
		$cache = md5 ( $view . '@@' . $layout . '@@' . $cacheid . '@@' . $compileid );
		return $this->cache_path . substr ( $cache, 0, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 2, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 4, 2 ) . DIRECTORY_SEPARATOR . substr ( $cache, 6 ) . '.html';
	}
}
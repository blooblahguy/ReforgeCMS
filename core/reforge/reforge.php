<?php

class Reforge extends \Prefab {
	public $root;

	function __construct() {
		$this->root = $_SERVER['DOCUMENT_ROOT'];

		spl_autoload_register([$this,'autoload']);
	}

	/**
	*	Convert backslashes to slashes
	*	@return string
	*	@param $str string
	**/
	function fixslashes($str) {
		return $str?strtr($str,'\\','/'):$str;
	}

	/**
	 * Namespace aware autoloader
	 * Uses namespace as relative folder
	 */
	function autoload($class) {
		$class = $this->fixslashes(ltrim($class,'\\'));
		$class = strtolower($class);
		list($namespace, $class) = explode("/", $class);
		if (! $class) {
			$class = $namespace;
		}

		$path = $this->root."/core/controllers/$class.php";
		$rf_path = $this->root."/core/reforge/$class.php";

		if (is_file($path)) {
			require $path;
		} elseif (is_file($rf_path)) {
			require $rf_path;
		}
	}
}

return Reforge::instance();
<?php

class Reforge extends \Prefab {
	public $root;

	function __construct() {
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		spl_autoload_register([$this, 'autoload']);
		Session::instance();
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
		
		$model = $this->root."/core/models/".$class.".php";
		$controller = $this->root."/core/controllers/".$class.".php";
		$reforge = $this->root."/core/reforge/".$class.".php";

		if (is_file($model)) {
			require $model;
		} elseif (is_file($controller)) {
			require $controller;
		} elseif (is_file($reforge)) {
			require $reforge;
		}
	}
}



return Reforge::instance();
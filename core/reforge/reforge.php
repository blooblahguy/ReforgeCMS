<?php

class Reforge extends \Prefab {
	public $root;
	public $session;

	function __construct() {
		global $root;
		global $cron;
		$this->root = $root;
		// $this->session = new Session();
		require_once ( 'session.php' );
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Get IP address
	 * @return string
	 */
	function ip() {
		$ipaddress = '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	/**
	 *	Convert backslashes to slashes
	 *	@return string
	 *	@param $str string
	 **/
	function fixslashes( $str ) {
		return $str ? strtr( $str, '\\', '/' ) : $str;
	}

	/**
	 * Namespace aware autoloader
	 * Uses namespace as relative folder
	 */
	function autoload( $class ) {
		$class = $this->fixslashes( ltrim( $class, '\\' ) );
		$class = strtolower( $class );
		$namespace = "";

		if ( str_contains( $class, "/" ) ) {
			list( $namespace, $class ) = explode( "/", $class );
		} else if ( ! $class ) {
			$class = $namespace;
		}

		$model = $this->root . "/core/models/" . $class . ".php";
		$reforge = $this->root . "/core/reforge/" . $class . ".php";

		if ( is_file( $model ) ) {
			require $model;
		} elseif ( is_file( $reforge ) ) {
			require $reforge;
		}
	}
}



return Reforge::instance();
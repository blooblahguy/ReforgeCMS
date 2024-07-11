<?php

class Session extends \Prefab {
	function __construct() {
		session_start();
	}

	function set( $key, $value = "" ) {
		session_start();
		$_SESSION[ $key ] = $value;
		session_write_close();
	}

	function clear( $key ) {
		session_start();
		unset( $_SESSION[ $key ] );
		session_write_close();
	}

	function get( $key ) {
		session_start();
		$val = isset( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : null;
		session_write_close();
		return $val;
	}

	function destroy() {
		session_destroy();
	}
}

function session() {
	global $reforge;
	return Session::instance();
}
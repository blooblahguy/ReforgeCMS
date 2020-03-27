<?

class Session extends Prefab {
	private $sid;
	function __construct() {
		session_start();
	}

	function set($key, $value = "") {
		session_start();
		$_SESSION[$key] = $value;	
		session_write_close();
	}

	function clear($key) {
		session_start();
		unset($_SESSION[$key]);	
		session_write_close();
	}

	function get($key) {
		session_start();
		return $_SESSION[$key];
		session_write_close();
	}

	function destroy() {
		session_destroy();
	}
}

function session() {
	return Session::instance();
}


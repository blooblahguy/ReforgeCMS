<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	# config file exmaple

	## MongoDB Database
	$config = array();
	$config["database_host"] = "localhost";
	$config["database_port"] = "3306";
	$config["database"] = "reforge";
	$config["database_user"] = "root";
	$config["database_password"] = "";
	$config["options"] = array();

	## Misc variables
	$config["salt"] = "bdg_lightweight";

	## Admin Location
	$config["admin_path"] = "/admin";

	## Helpers
	$admin_path = $config['admin_path'];
	$root = $_SERVER['DOCUMENT_ROOT'];
	$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
	list($CONTROLLER) = explode("/", $PATH);

	return $config;
?>
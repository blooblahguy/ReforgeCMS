<?
//===================================
## MySQL Connection - Common
//===================================
	$config = array();
	$config["database"] = "reforge";
	$config["database_user"] = "root";
	$config["database_password"] = "";
//===================================
## MySQL Connection - Uncommon
//===================================
	$config["database_host"] = "localhost";
	$config["database_port"] = "3306";

//===================================
## Misc Variables
//===================================
	$config["salt"] = "bdg_lightweight";

	return $config;
?>
<?
function rf_config() {
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
	## SMTP Configuration
	//===================================
	$config["smtp_enable"] = false;
	$config["smtp_host"] = "smtp.gmail.com";
	$config["smtp_port"] = 587;
	$config["smtp_user"] = "";
	$config["smtp_password"] = "";
	$config['smtp_from'] = array("info@domain.com", "Web Info");
	$config['smtp_replyto'] = array("info@domain.com", "Web Info");

	//===================================
	## Misc Variables
	//===================================
	$config["salt"] = "reforge";

	return $config;
}

return rf_config();
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
	## Email Configuration
	//===================================
	$config['email_from'] = array("info@domain.com", "Web Info");
	$config['email_replyto'] = array("info@domain.com", "Web Info");

	//===================================
	## SMTP Configuration
	//===================================
	$config["smtp_enable"] = false;
	$config["smtp_host"] = "smtp.gmail.com";
	$config["smtp_port"] = 587;
	$config["smtp_user"] = "";
	$config["smtp_password"] = "";
	

	//===================================
	## Misc Variables
	//===================================
	$config["salt"] = "reforge";
	$config["environment"] = "production";

	return $config;
}

return rf_config();
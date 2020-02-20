<?
	// Users Table
	$schema->add("users", array(
		"username" => array(
			"type" => "VARCHAR(256)"
		), 
		"email" => array(
			"type" => "VARCHAR(256)",
			"unique" => true,
		),
		"password" => array(
			"type" => "VARCHAR(256)"
		),
		"role_id" => array(
			"type" => "INT(7)"
		),
		"last_login" => array(
			"type" => "DATETIME",
		)
	));

	// Login Cookies
	$schema->add("login_cookies", array(
		"token" => array(
			"type" => "VARCHAR(256)"
		), 
		"user_id" => array(
			"type" => "INT(7)"
		), 
	));
?>
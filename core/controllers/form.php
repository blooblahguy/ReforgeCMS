<?php
// setup profile and login routes
if (logged_in()) {
	$core->route("GET|POST /logout", "User->logout");
} else {
	
}

class Form extends \RF\Mapper {
	function __construct() {
		parent::__construct("rf_posts", false, "rf_forms");
	}
}

$form = new Form();

add_shortcode("login_form", array($form, "login_form"));
add_shortcode("admin_login_form", array($form, "admin_login_form"));
add_shortcode("profile_form", array($form, "profile_form"));
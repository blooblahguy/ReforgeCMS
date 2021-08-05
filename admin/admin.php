<?php

class Admin extends \Prefab {
	function __construct() {
		global $core;

		$core->route("GET|POST /logout", "User->logout");
	}

	/**
	 * Display login form
	 */
	function login_form() {
		global $root;
		require $root."/admin/pages/views/login.php";
	}

	/**
	 * Process login form
	*/
	// function login() {
	// 	$user = new User();
	// 	$redirect = $_POST["redirect"];

	// 	if ( $user->login() ) {
	// 		// we're in here, redirect
	// 		\Alerts::instance()->success("Successfully logged in, welcome back {$user->name}");
	// 		redirect($redirect);
	// 	}

	// 	\Alerts::instance()->error("Invalid username or password");
	// 	redirect($redirect);
	// }
}

// Force logins
if (! logged_in()) {
	$core->route("GET /admin", "Admin->login_form");
	$core->route("GET /admin/*", "Admin->login_form");
	$core->route("POST /admin/login", "Forms->login_submit", 0, 64);
	$core->run();
	exit();
}

// Set basic request variables
$request["user_id"] = current_user()->id;
$request["user_role"] = current_user()->role_id;

// Header scripts
queue_script("/core/assets/js/quill.min.js", 5);
queue_script("/core/assets/js/admin.js", 25);

// allow for global cache clear
if (current_user()->can("administrator")) {
	$core->route("GET /admin/clear-cache", function ($core, $args) {
		// debug("WTF");
		// exit();
		resetCaches();
		$ref = $_SERVER['HTTP_REFERER'];
		redirect($ref);
	});
};

if (current_user()->can("manage_users")) {
	$core->route("GET /admin/mimic-user/@id", function($core, $args) {
		$id = $args['id'];
		session()->set("user_mimic", $id);
		redirect("/");
	});
};

// include the admin pages
require "init.php";


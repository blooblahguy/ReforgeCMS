<?
	include("functions.php");

	if (! $current_user->logged_in()) {
		$core->route("GET *", function($core, $args) {
			include("pages/views/login.php");
		});

		$core->route("POST /admin/login", "User::login", 0, 32);
		
		$core->run();
	} else {
		$admin_menu = array();
		$request = array();
		$request["user_id"] = $current_user->id;
		$request["user_role"] = $current_user->role_id;

		include("init.php");
		do_action("admin/user_logged_in", $current_user->id);

		// run routes now
		do_action("admin/init");
		$core->run();
	}

?>
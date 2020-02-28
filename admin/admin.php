<?
	$admin_root = dirname(__FILE__);
	require_once("functions.php");

	if (! $user->logged_in()) {
		$core->route("GET *", function($core, $args) {
			require_once("pages/views/login.php");
		});

		$core->route("POST /admin/login", "User::login", 0, 32);
		
		$core->run();
	} else {
		$admin_menu = array();
		$request = array();
		$request["user_id"] = $user->id;
		$request["user_role"] = $user->role_id;

		require_once("class-admin.php");
		do_action("admin/user_logged_in", $user->id);

		// Pages
		// require_once("pages/class-admin-pages.php");

		// run routes now
		do_action("admin/init");
		$core->run();
	}

?>
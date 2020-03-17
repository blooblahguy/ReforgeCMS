<?
	require "functions.php";

	if (! $current_user->logged_in()) {
		$core->route("GET *", function($core, $args) {
			require "pages/views/login.php";
		});

		$core->route("POST /admin/login", "User::login", 0, 32);
		
		$core->run();
	} else {
		$admin_menu = array();
		$request = array();
		$request["user_id"] = $current_user->id;
		$request["user_role"] = $current_user->role_id;

		require "init.php";
		do_action("admin/user_logged_in", $current_user->id);
		queue_script("/core/js/lazy.js");


		$core->route("GET /admin/clear-cache", function($core, $args) {
			Cache::instance()->reset();
			$ref = $_SERVER['HTTP_REFERER'];
			redirect($ref);
		});

		// run routes now
		do_action("admin/init");
		$core->run();
	}

?>
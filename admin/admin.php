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
		$request = array();
		$request["user_id"] = $user->id;
		$request["user_role"] = $user->role_id;

		do_action("admin/user_logged_in", $user->id);

		$page = array();
		$on_admin = true;
		$admin_menu = array();

		// Pages
		require_once("pages/class-admin-pages.php");

		// run routes now
		do_action("admin/init");
		$core->run();

		// get header in first
		do_action("admin/before_header");
		require_once("pages/views/header.php");

		// now include view
		// if ($core->get("view")) {
			do_action("admin/content");
		// }

		// lastly, footer
		do_action("admin/before_footer");
		queue_script("/admin/js/admin.js", 15);
		require_once("pages/views/footer.php");

	}

?>
<?
	require_once("functions.php");

	$admin_root = dirname(__FILE__);
	list($ADMIN, $MENU, $ACTION) = explode("/", $PATH);
	$MENUPAIR = $MENU;
	if ($ACTION) {
		$MENUPAIR = implode("/", array($MENU, $ACTION));
	}

	// debug($admin_root);
	if (! $user->logged_in()) {
		$core->route("GET *", function($core, $args) {
			require_once("pages/views/login.php");
		});
		$core->route("POST /admin/login", "User::login", 0, 32);
		
		$core->run();
	} else {
		$on_admin = true;
		$admin_menu = array();
		// Pages
		require_once("pages/class-admin-pages.php");

		do_action("admin/user_logged_in", $user->id);		

		// get header in first
		queue_script("/admin/js/admin.js", 15);
		do_action("admin/before_header");
		require_once("pages/views/header.php");

		// run routes now
		do_action("admin/init");
		$core->run();

		// now include view
		if ($core->get("view")) {
			do_action("admin/render_view", $core->get("view"));
			require_once($core->get("view"));
		}

		// lastly, footer
		do_action("admin/before_footer");
		require_once("pages/views/footer.php");
	}

?>
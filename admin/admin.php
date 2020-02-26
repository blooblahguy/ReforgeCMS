<?
	$admin_root = dirname(__FILE__);
	list($ADMIN, $MENU, $ACTION) = explode("/", $PATH);
	$MENUPAIR = $MENU;
	if ($ACTION) {
		$MENUPAIR = implode("/", array($MENU, $ACTION));
	}

	// debug($admin_root);
	if (! $user->logged_in()) {
		$core->route("GET *", function($core, $args) {
			require_once("controllers/views/login.php");
		});
		$core->route("POST /admin/login", "User::login", 0, 32);
		
		$core->run();
	} else {
		$on_admin = true;
		
		queue_script("/admin/js/admin.js", 15);

		// Include controllers
		require_once("controllers/controllers.php");

		// get header in first
		do_action("admin_before_header");
		require_once("controllers/views/header.php");

		// run routes now
		do_action("admin_init");
		$core->run();

		// now include view
		if ($core->get("view")) {
			do_action("admin_view_".$core->get("view"));

			require_once($core->get("view"));
		}

		// lastly, footer
		do_action("admin_before_footer");
		require_once("controllers/views/footer.php");
	}

?>
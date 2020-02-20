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
			require_once("views/login.php");
		});
		$core->route("POST /admin/login", "User::login");
		
		$core->run();
	} else {
		// Basic routes
		$core->route("GET /admin/logout", "User::logout");
		$core->route("GET /", "Posts->display_dashboard");

		// Include controllers
		require_once("controllers/_controllers.php");

		// get header in first
		require_once("views/header.php");

		// run routes now
		$core->run();
		
		// now include view
		if ($core->get("view")) {
			require_once($core->get("view"));
		}

		// lastly, footer
		require_once("views/footer.php");
	}

?>
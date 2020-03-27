<?
require "functions.php";

if (!$current_user->logged_in()) {
	$core->route("GET /admin", function ($core, $args) {
		require "pages/views/login.php";
	});
	$core->route("GET /admin/*", function ($core, $args) {
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
	queue_script("/core/js/quill.min.js", 5);
	queue_script("/core/js/admin.js", 15);

	if (current_user()->can("administrator")) {
		$core->route("GET /admin/clear-cache", function ($core, $args) {
			resetCaches();
			$ref = $_SERVER['HTTP_REFERER'];
			redirect($ref);
		});
	};
}

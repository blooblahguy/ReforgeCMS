<?
	$core = require_once("vendor/fatfree-core/base.php");
	$core->set("admin_path", $admin_path);
	$core->set("CACHE", true);
	$core->set("salt", $configuration["salt"]);
	require_once("controllers/alerts.php");
	$alert = new \Alerts;
	
	new Session();

	// Base Functionality
	require_once("hook.php");
	require_once("functions.php");
	require_once("schema.php");

	queue_script("/core/js/cash.js", 1);
	queue_script("/core/js/ajax.min.js", 3);
	queue_script("/core/js/core.js", 5);
	queue_script("/core/js/custom_fields.js", 10);
	
	// Database
	$db = new DB\SQL(
		"mysql:host={$configuration["database_host"]};port={$configuration["database_port"]};dbname={$configuration["database"]}",
		"{$configuration["database_user"]}",
		"{$configuration["database_password"]}"
	);

	// include custom fields
	require_once("custom-fields/rcf.php");

	// Models
	require_once("models/_models.php");

	// Controllers
	require_once("controllers/_controllers.php");

	$user = new User();
	$options = new Option();

	if ($options->count() == 0) {
		$core->route("GET *", function($core, $args) {
			require_once("setup.php");
		});
		$core->route("POST /setup", "Setup::process");
		
		$core->run();
	} else {

		// Determine Where we are now
		if ("/".$CONTROLLER == $admin_path) {
			// Administrator Area
			require_once($ROOT."/admin/admin.php");
		} else {
			// Functions
			$content = new Content();
			$view = new View();

			$core->run();

			// now include view
			if ($core->get("view")) {
				require_once($ROOT."/content/".$core->get("view"));
			}
		}
	}
?>
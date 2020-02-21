<?
	$core = require_once("vendor/fatfree-core/base.php");
	$core->set("admin_path", $admin_path);
	$core->set("CACHE", true);
	$core->set("salt", $configuration["salt"]);
	require_once("controllers/alerts.php");
	$alert = new \Alerts;
	
	new Session();

	// Base Functionality
	require_once("functions.php");
	require_once("schema.php");
	
	// Database
	$db = new DB\SQL(
		"mysql:host={$configuration["database_host"]};port={$configuration["database_port"]};dbname={$configuration["database"]}",
		"{$configuration["database_user"]}",
		"{$configuration["database_password"]}"
	);

	// include custom fields
	require_once("custom_fields/custom_fields.php");

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
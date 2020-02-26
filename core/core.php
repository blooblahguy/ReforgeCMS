<?
	$core = require_once("vendor/fatfree-core/base.php");
	require_once("functions.php");

	$core->set("admin_path", $admin_path);
	$core->set("CACHE", true);
	$core->set("salt", $configuration["salt"]);


	$class_paths = array();
	$class_paths["controllers"] = '/core/controllers/%s.php';
	$class_paths["models"] = '/core/models/%s.php';
	$class_paths["admin"] = '/admin/controllers/%1$s/%1$s.php';

	spl_autoload_register(function($class) {
		global $class_paths;
		$root = $_SERVER['DOCUMENT_ROOT'];
		$name = strtolower($class);

		foreach ($class_paths as $string) {
			$path = $root.sprintf($string, $name);

			if (file_exists($path)) {
				require_once($path);
			}
		}
	});

	$alert = new Alerts;
	
	new Session();

	// Base Functionality
	require_once("hook.php");
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
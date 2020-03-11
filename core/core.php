<?
	// Base Functionality
	require_once("functions.php");
	require_once("hook.php");

	// F3 Core
	$core = require_once("vendor/fatfree-core/base.php");
	$core->set("CACHE", true);
	$core->set("DEBUG", 3);
	$core->set("salt", $configuration["salt"]);
	
	new Session();
	require_once("reforge/class-alerts.php");
	require_once("reforge/class-db.php");
	require_once("reforge/class-model.php");
	require_once("reforge/class-schema.php");

	// Autoloader
	$class_paths = array();
	$class_paths["reforge"] = '/core/reforge/%s.php';
	$class_paths["controllers"] = '/core/controllers/%s.php';
	spl_autoload_register(function($class) {
		global $root;

		$path = $root."/core/controllers/$class.php";

		if (file_exists($path)) {
			require_once($path);
		}
	});

	// Database
	$db = new RFDB(
		"mysql:host={$configuration["database_host"]};port={$configuration["database_port"]};dbname={$configuration["database"]}",
		"{$configuration["database_user"]}",
		"{$configuration["database_password"]}",
		array(
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_PERSISTENT => TRUE,
			\PDO::MYSQL_ATTR_COMPRESS => TRUE
		)
	);

	queue_script("/core/js/cash.js", 1);
	queue_script("/core/js/ajax.min.js", 3);
	queue_script("/core/js/core.js", 5);
	queue_script("/core/js/custom_fields.js", 10);
	
	// include custom fields
	require_once("custom-fields/rcf.php");

	$user = new User();
	$user->get_current_user();

	$option = new Option();
	$options = $option->load_all();

	if (count($options) == 0) {
		$core->route("GET *", function($core, $args) {
			require_once("setup.php");
		});
		$core->route("POST /setup", "Setup::process");
		
		$core->run();
	} else {
		// Determine Where we are now
		if ("/".$CONTROLLER == "/admin") {
			// Administrator Area
			require_once($root."/admin/admin.php");
		} else {
			// Functions
			// $content = new Content();

			$core->run();

			// now include view
			if ($core->get("view")) {
				require_once($root."/content/".$core->get("view"));
			}
		}
	}
?>
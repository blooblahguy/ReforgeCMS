<?
	// Base Functionality
	require_once("functions.php");
	require_once("hook.php");

	// F3 Core
	$core = require_once("vendor/fatfree-core/base.php");
	new Session();

	// quick variables
	$core->set("CACHE", true);
	$core->set("salt", $configuration["salt"]);

	// Autoloader
	$class_paths = array();
	$class_paths["controllers"] = '/core/controllers/%s.php';
	$class_paths["models"] = '/core/models/%s.php';
	$class_paths["admin"] = '/admin/controllers/%1$s/%1$s.php';
	spl_autoload_register(function($class) {
		global $class_paths, $core, $db;
		$root = $_SERVER['DOCUMENT_ROOT'];
		$name = strtolower($class);

		foreach ($class_paths as $string) {
			$path = $root.sprintf($string, $name);

			if (file_exists($path)) {
				require_once($path);
			}
		}
	});	

	// Database
	$db = new DB\SQL(
		"mysql:host={$configuration["database_host"]};port={$configuration["database_port"]};dbname={$configuration["database"]}",
		"{$configuration["database_user"]}",
		"{$configuration["database_password"]}",
		array(
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_PERSISTENT => TRUE,
			\PDO::MYSQL_ATTR_COMPRESS => TRUE
		)
	);
	
	// models
	require_once("models/_models.php");

	queue_script("/core/js/cash.js", 1);
	queue_script("/core/js/ajax.min.js", 3);
	queue_script("/core/js/core.js", 5);
	queue_script("/core/js/custom_fields.js", 10);
	
	// include custom fields
	require_once("custom-fields/rcf.php");

	$user = new User();
	$user->get_current_user();

	$options = new Option();

	if ($options->count() == 0) {
		$core->route("GET *", function($core, $args) {
			require_once("setup.php");
		});
		$core->route("POST /setup", "Setup::process");
		
		$core->run();
	} else {
		// Determine Where we are now
		if ("/".$CONTROLLER == "/admin") {
			// Administrator Area
			require_once($ROOT."/admin/admin.php");
		} else {
			// Functions
			$content = new Content();

			$core->run();

			// now include view
			if ($core->get("view")) {
				require_once($ROOT."/content/".$core->get("view"));
			}
		}
	}
	debug($db);
?>
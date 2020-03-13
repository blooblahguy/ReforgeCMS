<?
	// Base Functionality
	include("functions.php");
	include("hook.php");

	// F3 Core
	$core = include("vendor/fatfree-core/base.php");
	$core->set("CACHE", true);
	$core->set("DEBUG", 1);
	$core->set("UI", $root."/content/");
	$core->set("salt", $configuration["salt"]);

	// Autoloader
	$class_paths = array();
	$class_paths["reforge"] = '/core/reforge/%s.php';
	$class_paths["controllers"] = '/core/controllers/%s.php';
	spl_autoload_register(function($class) {
		global $root;

		$path = $root."/core/controllers/$class.php";

		if (file_exists($path)) {
			include($path);
		}
	});

	include("reforge/class-alerts.php");
	include("reforge/class-db.php");
	include("reforge/class-model.php");
	include("reforge/class-schema.php");
	include("reforge/session.php");

	// media manager
	include("reforge/media/media.php");
	include("reforge/media/file.php");
	RF_Media::instance();

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
	queue_script("/core/custom-fields/js/custom_fields.js", 10);
	
	// include custom fields
	include("custom-fields/rcf.php");

	$meta = new Meta();

	$media = Media::instance();
	$media->add_size("thumbnail", 120, null, false);
	$media->add_size("medium", 400, null, false);
	$media->add_size("large", 1000, null, false);
	$media->add_size("hero", 1400, 400);
	
	$current_user = new User();
	$current_user->get_user();

	$option = new Option();
	$options = $option->load_all();
	
	if (count($options) == 1) {
		$core->route("GET *", function($core, $args) {
			include("setup.php");
		});
		$core->route("POST /setup", "Setup::process");
		
		$core->run();
	} else {
		$core->route("GET|POST /logout", "User->logout");

		// Determine Where we are now
		if ("/".$CONTROLLER == "/admin") {
			// Administrator Area
			include($root."/admin/admin.php");
		} else {
			// Theme manager
			Content::instance();

			$core->run();
		}
	}
?>
<?
	// F3 Core
	$core = require $root."/core/vendor/fatfree-core/base.php";
	$core->set("DEBUG", 1);
	$core->set("UI", $root."/content/");
	$core->set("salt", $configuration["salt"]);

	require "$root/core/globals.php";
	require "$root/core/functions.php";
	require "$root/core/hook.php";
	require "$root/core/reforge/cache.php";
	require "$root/core/reforge/db/mapper.php";
	// require "$root/core/reforge/class-alerts.php";
	// require "$root/core/reforge/class-db.php";
	// require "$root/core/reforge/class-model.php";
	// require "$root/core/reforge/class-schema.php";
	// require "$root/core/reforge/session.php";
	require "$root/core/reforge/media/media.php";
	require "$root/core/reforge/media/file.php";
	require "$root/core/custom_fields/rcf.php";
	
	// media manager
	$media = RF_Media::instance();
	$media->add_size("medium", 400, null, false);
	$media->add_size("large", 1000, null, false);
	$media->add_size("hero", 1600, 400);

	// Autoloader
	spl_autoload_register(function($class) {
		global $root;
		$class = strtolower($class);
		$path = $root."/core/controllers/$class.php";
		$rf_path = $root."/core/reforge/$class.php";

		if (file_exists($path)) {
			require $path;
		} elseif (file_exists($rf_path)) {
			require $rf_path;
		}
	});	

	new Session();

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
	queue_script("/core/js/cash.js", 1);
	queue_script("/core/js/ajax.min.js", 3);
	queue_script("/core/js/core.js", 16);
	queue_script("/core/custom_fields/js/custom_fields.js", 18);

	$current_user = new User();
	$current_user->get_user();

	$option = new Option();
	$options = $option->load_all();
	
	if (count($options) == 1) {
		$core->route("GET *", function($core, $args) {
			require "setup.php";
		});
		$core->route("POST /setup", "Setup::process");
		
		$core->run();
	} else {
		do_action("init");

		// Load Plugins
		$plugins = unserialize($options['active_plugins']);
		if (! $plugins) {$plugins = array(); }
		foreach ($plugins as $path) {
			// require $path;
		}
		$core->route("GET|POST /logout", "User->logout");

		// Determine Where we are now
		if ("/".$CONTROLLER == "/admin") {
			// Administrator Area
			require "$root/admin/admin.php";
		} else {
			// Theme manager
			Content::instance();

			$core->run();
		}
	}
?>
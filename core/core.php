<?php

// F3 Core
$core = include $root . "/core/vendor/fatfree-core/base.php";
$core->set("DEBUG", 1);
$core->set("salt", $configuration["salt"]);

require "$root/core/globals.php";
require "$root/core/functions.php";
require "$root/core/hook.php";
require "$root/core/reforge/cache.php";
require "$root/core/reforge/db/mapper.php";
require "$root/core/reforge/media/media.php";
require "$root/core/reforge/media/file.php";
require "$root/core/custom_fields/rcf.php";

// Autoloader
spl_autoload_register(function ($class) {
	global $root;
	$class = strtolower($class);
	list($namespace, $class) = explode("\\", $class);
	if (! $class) {
		$class = $namespace;
	}
	$path = $root . "/core/controllers/$class.php";
	$rf_path = $root . "/core/reforge/$class.php";

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
		\PDO::ATTR_PERSISTENT => true,
		\PDO::MYSQL_ATTR_COMPRESS => true,
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
	$core->route("GET *", function ($core, $args) {
		require "setup.php";
	});
	$core->route("POST /setup", "Setup::process");

	$core->run();
} else {
	$core->route("GET|POST /logout", "User->logout");

	// add plugin load to init
	add_action("load_plugins", "load_plugins", 12);
	
	// Determine Where we are now
	if ("/" . $CONTROLLER == "/admin") {
		require "$root/admin/admin.php";
		do_action("load_plugins");
		do_action("init");
		do_action("admin");
		do_action("admin/init");
		$core->run();
	} else {
		// Theme manager
		do_action("load_plugins");
		do_action("init");
		do_action("front");
		Content::instance();
		$core->run();
	}
}

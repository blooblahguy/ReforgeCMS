<?php
$configuration = require $root."/reforge-config.php";
// F3 Core
$core = require $root."/core/fatfree/base.php";
$core->set("DEBUG", 1);
$core->set("salt", $configuration["salt"]);

// Reforge Core
$reforge = include $root."/core/reforge/session.php";
$reforge = include $root."/core/reforge/reforge.php";
// include $root."/core/reforge/router.php";
include $root."/core/reforge/magic.php";

// Route::instance()->route("GET|POST /about-us", function() {
// 	echo "test";
// });
// Route::instance()->run();

// core stuff
include $root."/core/database.php";
include $root."/core/globals.php";
include $root."/core/functions.php";
include $root."/core/hook.php";
include $root."/core/reforge/media.php";

// Controllers
foreach (glob($root."/core/controllers/*.php") as $filename) {
	include $filename;
}

// add plugin load to init
add_action("load_plugins", "load_plugins", 12);
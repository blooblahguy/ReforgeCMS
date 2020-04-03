<?php

// F3 Core
$core = require $root."/core/vendor/fatfree-core/base.php";
$core->set("DEBUG", 1);
$core->set("salt", $configuration["salt"]);

// Reforge Core
$reforge = require $root."/core/reforge/reforge.php";
require $root."/core/reforge/session.php";
require $root."/core/database.php";
require $root."/core/globals.php";
require $root."/core/functions.php";
require $root."/core/hook.php";
require $root."/core/models/user.php";
require $root."/core/models/form.php";
require $root."/core/reforge/media.php";
require $root."/core/controllers/custom_fields/rcf.php";

$option = new Option();
$options = $option->load_all();

// Require site setup
if (count($options) == 0) {
	$core->route("GET *", "Setup->index");
	$core->route("POST /setup", "Setup->process");
	$core->run();
	exit();
}

// queue basic javascript
queue_script("/core/assets/js/cash.js", 1);
queue_script("/core/assets/js/ajax.min.js", 3);
queue_script("/core/assets/js/core.js", 16);
queue_script("/core/assets/js/custom_fields.js", 18);

// add plugin load to init
add_action("load_plugins", "load_plugins", 12);

// global logout endpoint
$core->route("GET|POST /logout", "User->logout");

// Admin Area
if ("/" . $CONTROLLER == "/admin") {
	require $root."/admin/functions.php";
	require $root."/admin/admin.php";
	// common actions
	do_action("load_plugins");
	do_action("init");
	do_action("admin/init");
} else {
	do_action("load_plugins");
	do_action("init");
	do_action("front/init");
	Content::instance();
}

$core->run();
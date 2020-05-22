<?php

// F3 Core
$core = require $root."/core/fatfree/base.php";
$core->set("DEBUG", 1);
$core->set("salt", $configuration["salt"]);
require $root."/core/reforge/session.php";
Session::instance();

// Reforge Core
$reforge = require $root."/core/reforge/reforge.php";
require $root."/core/database.php";
require $root."/core/globals.php";
require $root."/core/functions.php";
require $root."/core/hook.php";
require $root."/core/reforge/media.php";

$option = new Option();
$options = $option->load_all();

// controllers
require $root."/core/controllers/custom-fields.php";
require $root."/core/controllers/forms.php";
require $root."/core/controllers/partials.php";
require $root."/core/controllers/content.php";

// Require site setup
if (count($options) == 0) {
	$core->route("GET *", "Setup->index");
	$core->route("POST /setup", "Setup->process");
	$core->run();
	exit();
}

// queue basic javascript
queue_script("/core/assets/js/lazy.js");
queue_script("/core/assets/js/quill.min.js", 5);
queue_script("/core/assets/js/cash.js", 1);
queue_script("/core/assets/js/ajax.min.js", 3);
queue_script("/core/assets/js/core.js", 16);
queue_script("/core/assets/js/custom_fields.js", 18);

// add plugin load to init
add_action("load_plugins", "load_plugins", 12);

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
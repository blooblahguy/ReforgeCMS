<?php

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
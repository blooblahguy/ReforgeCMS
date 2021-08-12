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
queue_script("/core/assets/js/cash.js", 1);
// queue_script("/core/assets/js/ajax.min.js", 3);
queue_script("/core/assets/js/sortable.min.js", 18);
queue_script("/core/assets/js/core.js", 16);
queue_script("/core/assets/js/custom_fields.js", 18);

// Admin Area
locate_template(array("functions.php"), true, false);

if ($CONTROLLER == "admin") {
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

// $core->route("GET /glade/import", function() {
// 	include "import.php";
// });

// $form = new Form();
if (! logged_in()) {
	// debug('test');
	$core->route("GET /verify", function() {
		$code = $_GET['code'];

		$verify = new VerifyCode();
		$verify->load("*", array("code = :code", ":code" => $code));

		if ($verify->id) {
			$user = new User();
			$user->load("*", array("id = :id", ":id" => $verify->user_id));
			$user->verified = 1;
			$user->save();

			$verify->erase();

			Alerts::instance()->success("Email successfuly verified, you can log in now.");
			redirect("/login");
		} else {
			Alerts::instance()->error("Invalid verification code");
			redirect("/");
		}
	});
}

$core->run();

$reforge_load_time += hrtime(true);
$reforge_load_time = $reforge_load_time / 1e+6;
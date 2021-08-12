<?php

// User mimicing
$core->route("GET /admin/reset-user", function($core, $args) {
	session()->clear("user_mimic");
	$ref = $_SERVER['HTTP_REFERER'];
	redirect($ref);
});

add_action("rf_footer", function() {
	if (session()->get("user_mimic")) {
		$user = current_user();
		?>
		<div class="user_mimic text-center">Currently browsing site as <strong><?= $user->username; ?></strong>. <a href="/admin/reset-user">Return to normal user.</a></div>
		<?
	}
});

// Allow for avatar manual refresh
if (current_user()->can("administrator")) {
	$core->route("GET /admin/users/reset_avatar/@id", function($core, $args) {
		$user = get_user($args['id']);
		$user->check_avatar();

		redirect();
	});
}
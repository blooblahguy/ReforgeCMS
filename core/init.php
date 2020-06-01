<?php
$configuration = require $root."/reforge-config.php";

// F3 Core
$core = require $root."/core/fatfree/base.php";
$core->set("DEBUG", 1);
$core->set("salt", $configuration["salt"]);

// Reforge Core
$reforge = include $root."/core/reforge/reforge.php";
$reforge = include $root."/core/reforge/magic.php";

// core stuff
include $root."/core/database.php";
include $root."/core/globals.php";
include $root."/core/functions.php";
include $root."/core/hook.php";
include $root."/core/reforge/media.php";

function add_recaptcha($label = "Submit") {
	queue_script('https://www.google.com/recaptcha/api.js');

	?>
	<script>
		function onSubmit(token) {
			document.getElementById("recaptcha-form").submit();
		}
	</script>
	<button class="btn-secondary g-recaptcha" 
        data-sitekey="6LdLF_8UAAAAALGVy69oBTwtqNRhn3--dgon9_DT" 
        data-callback='onSubmit'><?= $label; ?></button>
	<?
}

// Controllers
foreach (glob($root."/core/controllers/*.php") as $filename) {
	include $filename;
}

// add plugin load to init
add_action("load_plugins", "load_plugins", 12);
<?php


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

// controllers
// include $root."/core/controllers/comments.php";
// include $root."/core/controllers/content.php";
// include $root."/core/controllers/custom-fields.php";
// include $root."/core/controllers/forms.php";
// include $root."/core/controllers/options.php";
// include $root."/core/controllers/partials.php";

foreach (glob("core/controllers/*.php") as $filename) {
	include $filename;
}

// add plugin load to init
add_action("load_plugins", "load_plugins", 12);
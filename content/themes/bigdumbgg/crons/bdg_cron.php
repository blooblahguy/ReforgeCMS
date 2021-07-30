<?php

error_reporting(E_ALL);

print_r(dirname(dirname(__FILE__)));
print_r(realpath(dirname(__FILE__)));

$root = realpath(dirname(__FILE__).'/../../../../');

// include reforge core
include $root."/core/init.php";
do_action("load_plugins");
do_action("init");

// cron jobs
// include "cron_progression.php";
// include "cron_twitter.php";
include "cron_twitch.php";
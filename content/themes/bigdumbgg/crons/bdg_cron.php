<?php

error_reporting(E_ALL);

// echo dirname(dirname(__FILE__));
// echo "\n";
// echo realpath(dirname(__FILE__));
// echo "\n";
// echo dirname(__FILE__, 2);

$root = $_SERVER['DOCUMENT_ROOT'];
if (! $_SERVER['DOCUMENT_ROOT']) {
	$root = '/home/runcloud/webapps/bigdumb';
}

// include reforge core
require $root."/core/init.php";
do_action("load_plugins");
do_action("init");

// cron jobs
// include "cron_progression.php";
include "cron_twitter.php";
require "cron_twitch.php";
<?php
	$expires = 60*60*24; // how long to cache in secs..
	header('Content-Type: text/css');
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	$root = $_SERVER['DOCUMENT_ROOT'];
	$out_file = "openskull.min.css";

	$sheets = array();
	$sheets[] = $root."/core/assets/css/openskull/_defaults.scss";
	$sheets[] = $root."/core/assets/css/openskull/_functions.scss";
	$sheets[] = "../_variables.scss";
	$sheets[] = $root."/core/assets/css/openskull/_variables.scss";
	$sheets[] = $root."/core/assets/css/openskull/_reset.scss";
	$sheets[] = $root."/core/assets/css/openskull/_colors.scss";
	$sheets[] = $root."/core/assets/css/openskull/_buttons.scss";
	$sheets[] = $root."/core/assets/css/openskull/_typography.scss";
	$sheets[] = $root."/core/assets/css/openskull/_helpers.scss";
	$sheets[] = $root."/core/assets/css/openskull/_forms.scss";
	$sheets[] = $root."/core/assets/css/openskull/_ui.scss";
	$sheets[] = $root."/core/assets/css/openskull/_grid.scss";
	$sheets[] = "../style.scss";

	require "build.php";
?>
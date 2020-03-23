<?php
	header('Content-Type: text/css');
	ini_set("display_errors", 1);
	error_reporting(E_ALL);

	$root = $_SERVER['DOCUMENT_ROOT'];
	$out_file = "openskull.min.css";

	$sheets = array();
	$sheets[] = $root."/core/css/openskull/_defaults.scss";
	$sheets[] = $root."/core/css/openskull/_functions.scss";
	$sheets[] = "../_variables.scss";
	$sheets[] = $root."/core/css/openskull/_variables.scss";
	$sheets[] = $root."/core/css/openskull/_reset.scss";
	$sheets[] = $root."/core/css/openskull/_colors.scss";
	$sheets[] = $root."/core/css/openskull/_buttons.scss";
	$sheets[] = $root."/core/css/openskull/_typography.scss";
	$sheets[] = $root."/core/css/openskull/_helpers.scss";
	$sheets[] = $root."/core/css/openskull/_forms.scss";
	$sheets[] = $root."/core/css/openskull/_ui.scss";
	$sheets[] = $root."/core/css/openskull/_grid.scss";
	$sheets[] = "../style.scss";

	require "build.php";
?>
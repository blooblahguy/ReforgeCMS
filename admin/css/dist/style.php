<?php
	header('Content-Type: text/css');
	$root = $_SERVER['DOCUMENT_ROOT'];
	$config = include $root."reforge-config.php";

	$theme = "default";
	if (isset($_GET['theme'])) {
		$theme = $_GET['theme'];
	}

	$out_file = "{$theme}_openskull.min.css";


	if ($config['environment'] == "production") {
		require $out_file;
		exit();
	}


	
	$sheets = array();
	$sheets[] = $root."/core/assets/css/openskull/_defaults.scss";
	$sheets[] = $root."/core/assets/css/openskull/_functions.scss";
	$sheets[] = "../_variables_{$theme}.scss";
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
	$sheets[] = "../editor_{$theme}.scss";

	require "build.php";

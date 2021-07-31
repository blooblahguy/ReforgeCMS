<?php

function get_option($key, $force = false) {
	global $options;
	global $db;

	if ($force) {
		$option = new Option();
		$rs = $db->exec("SELECT * FROM rf_options WHERE `key` = '{$key}'");
		// debug($rs);
		$options[$key] = $rs[0]['value'];//->value;
	}

	if (is_serial($options[$key])) {
		return unserialize($options[$key]);
	}

	return $options[$key];
}
function set_option($key, $value = "") {
	global $options;

	if (is_array($value)) {
		$value = serialize($value);
	}
	
	// debug($value);
	$option = new Option();
	if (isset($options[$key])) {
		// don't bother updating if the values are the same
		if ($options[$key] == $value) {
			return;
		}

		$option->load("*", array("`key` = :key", ":key" => $key));

		// debug("loading");
	}

	// debug("value", $value);
	
	$option->key = $key;
	$option->value = $value;
	// debug($option);

	$option->save();

	$options[$key] = $value;
}

$option = new Option();
$options = $option->load_all();
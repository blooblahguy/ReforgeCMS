<?php

function get_option($key) {
	global $options;
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

	$option = new Option();
	if (isset($options[$key])) {
		// don't bother updating if the values are the same
		if ($options[$key] == $value) {
			return;
		}
		$option->load("*", array("`key` = :key", ":key" => $key));
	}
	
	
	$option->key = $key;
	$option->value = $value;
	$option->save();

	$options[$key] = $value;
}

$option = new Option();
$options = $option->load_all();
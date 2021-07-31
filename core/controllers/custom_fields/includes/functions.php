<?php

function render_rcf_field($field, $options) {
	// debug($options);
	$value = $field[$options["name"]];
	$options['name'] = "rcf_fields[{$field['key']}][{$options['name']}]";

	// debug($settings['name']);

	render_html_field($value, $options);
}

function split_uid($uid) {
	global $request;
	if (! $uid) {
		$uid = "post_".$request['page_id'];
	}
	if ($uid == "settings") {
		$uid = "settings_0";
	}

	// split by last underscore, beginning is post type, end is post id
	$type = substr($uid, 0, strrpos($uid,'_'));
	$id = substr($uid, (strrpos($uid,'_') + 1));
	if ($id === null) {
		$id = $type;
		$type = null;
		$uid = "post_{$id}";
	}

	return array($type, $id);
}

function get_field($key, $uid = false) {
	global $request;
	list($type, $id) = split_uid($uid);

	if (! isset($request['fields'][$uid])) {
		get_fields($uid);
	}

	return $request['fields'][$uid][$key];
}

function get_fields($uid = false) {
	global $request;
	list($type, $id) = split_uid($uid);

	if ($id == null) { return array(); }

	if (! isset($request['fields'][$uid])) {
		$request['fields'][$uid] = RCF::instance()->get_fields($type, $id);
	}

	return $request['fields'][$uid];
}

function rcf_get_field_types() {
	return RCF::instance()->get_field_types();
}

// include file with arguements
function rcf_get_template( $file = '', $args = array() ) {
	$path = RCF()->directory."/templates/".$file;
	
	// allow view file name shortcut
	if ( substr($file, -4) !== '.php' ) {
		$path .= ".php";
	}
	
	// include
	if ( file_exists($path) ) {
		extract( $args );
		require  $path ;
	}
}



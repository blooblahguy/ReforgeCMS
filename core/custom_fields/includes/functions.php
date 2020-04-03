<?

function split_uid($uid) {
	global $request;
	if (! $uid) {
		$uid = "post_".$request['page_id'];
	}
	if ($uid == "settings") {
		$uid = "settings_0";
	}

	list($type, $id) = explode("_", $uid);
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

function rcf_render_field_setting($field, $settings) {
	$type = $settings["type"]; 
	$value = $field[$settings["name"]];
	$settings['name'] = "rcf_fields[{$field['key']}][{$settings['name']}]"; // field_key1231[name]
	$settings['layout'] = $settings['grid'];

	render_admin_field($value, $settings);	
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



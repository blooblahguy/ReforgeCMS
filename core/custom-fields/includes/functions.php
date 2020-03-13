<?

function rcf_get_field_types() {
	return RCF::instance()->get_field_types();
}

function rcf_render_field_setting($field, $settings) {
	$type = $settings["type"]; 
	$value = $field[$settings["name"]];
	$settings['name'] = "rcf_fields[{$field['key']}][{$settings['name']}]"; // field_key1231[name]

	render_admin_field($value, $settings);
}

// include file with arguements
function rcf_get_template( $file = '', $args = array() ) {
	$path = RCF()->directory."/templates/".$file;
	
	// allow view file name shortcut
	if( substr($file, -4) !== '.php' ) {
		$path .= ".php";
	}
	
	// include
	if( file_exists($path) ) {
		extract( $args );
		require( $path );
	}
}


?>
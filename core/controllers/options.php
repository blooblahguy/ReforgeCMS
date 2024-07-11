<?php

function isJson( $string ) {
	json_decode( $string );
	return json_last_error() === JSON_ERROR_NONE;
}

function get_option( $key, $force = false ) {
	global $options;
	global $db;

	if ( $force ) {
		$option = new Option();
		$rs = $db->exec( "SELECT * FROM rf_options WHERE `key` = '{$key}'" );
		// debug($rs);
		$options[ $key ] = $rs[0]['value'];//->value;
	}

	$value = isset( $options[ $key ] ) ? $options[ $key ] : null;

	if ( $value === null ) {
		return null;
	}

	if ( is_serial( $value ) ) {
		return unserialize( $value );
	} elseif ( isJson( $value ) ) {
		return json_decode( $value, true );
	}

	return $options[ $key ];
}
function set_option( $key, $value = "" ) {
	global $options;
	// global $db;

	// $db->exec("ALTER TABLE rf_options CONVERT TO CHARACTER SET utf8mb4;");
	# For each database:
	// $db->exec("ALTER DATABASE bigdumb_dev CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;");
	# For each table:
	// $db->exec("ALTER TABLE rf_options CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
	# For each column:
	// $db->exec("ALTER TABLE rf_options CHANGE column_name column_name VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

	if ( is_array( $value ) ) {
		$value = json_encode( $value );
		// $value = serialize($value);
	}

	// debug($value);
	$option = new Option();
	// $id = 0;
	if ( isset( $options[ $key ] ) ) {
		// don't bother updating if the values are the same
		if ( $options[ $key ] == $value ) {
			return;
		}

		$option->load( "*", array( "`key` = :key", ":key" => $key ) );
		// $id = $option->id;

		// debug("loading");
	}

	// debug("value", $value);

	// $option->id = $id;
	$option->key = $key;
	$option->value = $value;

	// debug($option);
	debug( $option, $key, $value );

	$option->save();

	$options[ $key ] = $value;
}

$option = new Option();
$options = $option->load_all();
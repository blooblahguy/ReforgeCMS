<?
$source = RCF()->current_data;

// loop through field layout
echo "<div class='field_group row g1'>";
foreach ($fields as $field_id => $field) {

	// Create context aware key
	$key = $context;
	if ($index !== null) {
		$key .= "_{$index}_";
	}
	$key .= $field["slug"];

	// DATA
	// find data relevant to this field only
	$data = array();
	foreach ($source as $info) {
		if ($info['meta_key'] == $key) {
			$data = $info;
			break;
		}
	}

	rcf_get_template('group-field', array(
		'field' => $field,
		'context' => $key,
		"data" => $data,
	));
}
echo "</div>";

?>
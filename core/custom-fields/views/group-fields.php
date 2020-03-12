<?
// global $source;
// debug($source);
$source = RCF()->current_data;
// debug($source);
// $source = RCF()->load()
// debug($source[0]);
// $source = array();

// $source[] = array(
// 	"meta_key" => "repeater",
// 	"meta_value" => 2,
// 	"meta_info" => "field_5e5568ea32c29",
// );

// debug($source);
// 	$source[] = array(
// 		"meta_key" => "repeater_0_text_one",
// 		"meta_value" => "Text value in repeater",
// 		"meta_info" => "field_5e5568fc32c2b",
// 	);
// 	$source[] = array(
// 		"meta_key" => "repeater_0_sub_repeater",
// 		"meta_value" => 2,
// 		"meta_info" => "field_5e55690332c2c",
// 	);
// 		$source[] = array(
// 			"meta_key" => "repeater_0_sub_repeater_0_test1",
// 			"meta_value" => "1 test1 in subrepeater",
// 			"meta_info" => "field_5e55690c32c2d",
// 		);
// 		$source[] = array(
// 			"meta_key" => "repeater_0_sub_repeater_0_test2",
// 			"meta_value" => "1 test2 in subrepeater",
// 			"meta_info" => "field_5e55690d32c2e",
// 		);

// 		$source[] = array(
// 			"meta_key" => "repeater_0_sub_repeater_1_test1",
// 			"meta_value" => "2 test1 in subrepeater",
// 			"meta_info" => "field_5e55690c32c2d",
// 		);
// 		$source[] = array(
// 			"meta_key" => "repeater_0_sub_repeater_1_test2",
// 			"meta_value" => "2 test2 in subrepeater",
// 			"meta_info" => "field_5e55690d32c2e",
// 		);

// 	$source[] = array(
// 		"meta_key" => "repeater_1_text_one",
// 		"meta_value" => "Text value in repeater number two",
// 		"meta_info" => "field_5e5568fc32c2b",
// 	);
// 	$source[] = array(
// 		"meta_key" => "repeater_1_sub_repeater",
// 		"meta_value" => 0,
// 		"meta_info" => "field_5e55690332c2c",
// 	);

// $source[] = array(
// 	"meta_key" => "text_block",
// 	"meta_value" => "Text block bottom",
// 	"meta_info" => "field_5e5568ed32c2a",
// );

// debug($source);

// $source[] = array(
// 	"meta_key" => "blank_repeater",
// 	"meta_value" => 1,
// 	"meta_info" => "field_5e5568ed32c2a",
// );


?>
	<!-- <table>
		<thead>
			<tr>
				<th>meta_key</th>
				<th>meta_value</th>
				<th>meta_info</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($source as $field) { ?>
				<tr>
					<td><?= $field["meta_key"]; ?></td>
					<td><?= $field["meta_value"]; ?></td>
					<td><?= $field["meta_info"]; ?></td>
				</tr>
			<? } ?>
		</tbody>
	</table> -->
<?

// loop through field layout
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

	rcf_get_view('group-field', array(
		'field' => $field,
		'context' => $key,
		"data" => $data,
	));
}

?>
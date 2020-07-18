<?
$source = RCF()->current_data;
// debug($fields);
// debug($source);
?>

<div class="field_group_outer row<? if ($context != "") { echo " top"; } ?>">
	<div class="field_group os row g1">
		<?
		// loop through field layout
		foreach ($fields as $field_id => $field) {

			$key = $context;
			if (isset($index)) {
				$key .= "_{$index}_";
			}
			$key .= $field["slug"];

			if (isset($source[$key])) {
				$data = $source[$key];
			}

			rcf_get_template('group-result', array(
				'field' => $field,
				'context' => $key,
				"data" => $data,
			));
		}
		?>
	</div>
</div>


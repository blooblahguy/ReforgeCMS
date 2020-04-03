<?
$source = RCF()->current_data;
// debug($fields);
// debug($source);
?>

<div class="field_group_outer row<? if ($context != "") { echo " border"; } ?>">
	<? if ($context != "") { ?>
		<div class="group_drag os-min pad1 bg-light-grey">
			<i>drag_indicator</i>
		</div>
	<? } ?>
	<div class="field_group os row g1 pady1">
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

			rcf_get_template('group-field', array(
				'field' => $field,
				'context' => $key,
				"data" => $data,
			));
		}
		?>
	</div>
	<? if ($context != "") { ?>
		<div class="group_remove os-min pad1 bg-light-grey">
			<a href="#" class=""><i>remove_circle</i></a>
		</div>
	<? } ?>
</div>


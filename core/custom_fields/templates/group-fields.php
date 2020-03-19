<?
$source = RCF()->current_data;
// var_dump($context);

?>



<div class="field_group_outer row <? if ($context != "") { echo " border"; } ?>">
	<? if ($context != "") { ?>
		<div class="os-min pad1 bg-light-grey group_drag">
			<i>drag_indicator</i>
		</div>
	<? } ?>
	<div class="os pad1">
		<div class="field_group row g1">
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

				rcf_get_template('group-field', array(
					'field' => $field,
					'context' => $key,
					"data" => $data,
				));
			}
			?>
		</div>
	</div>
	<? if ($context != "") { ?>
		<div class="os-min pad1 bg-light-grey group_remove">
			<a href="#" class=""><i>remove_circle</i></a>
		</div>
	<? } ?>
</div>


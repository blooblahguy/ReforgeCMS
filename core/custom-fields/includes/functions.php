<?

function rcf_get_field_types() {
	return RCF::instance()->get_field_types();
}

function rcf_render_field_setting($field, $settings) {
	$type = $settings["type"]; 
	$name = "rcf_fields[{$field['key']}][{$settings['name']}]"; // field_key1231[name]
	$value = $field[$settings["name"]];
	$bind = "";
	if ($settings["bind"]) {
		$bind = " data-bind";
	}
	$choices = $settings["choices"];

	?>

	<div class="fieldset <?= $settings["class"]; ?>">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for=""><?= $settings['label']; ?></label>
			</div>
			<div class="os">
				<? if ($type == "checkbox") { ?>
					<input type="checkbox" name="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?>>
				<? } elseif ($type == "number") { ?>
					<input type="number" name="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?>>
				<? } elseif ($type == "text") { ?>
					<input type="text" name="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?>>
				<? } elseif ($type == "select") { 
					?>
					<select name="<?= $name; ?>" class="rcf_dropdown type" <?= $bind; ?>>
						<?
						foreach ($choices as $key => $option) { 
							if (is_array($option)) { ?>
								<optgroup label="<?= $key; ?>">
									<? foreach ($option as $skey => $label) { ?>
										<option value="<?= $skey; ?>" <? if ($skey == $value) {echo "selected"; } ?>><?= $label; ?></option>
									<? } ?>
								</optgroup>
							<? } else { ?>
								<option value="<?= $key; ?>" <? if ($key == $value) {echo "selected"; } ?>><?= $option; ?></option>
							<? } ?>
						<? } ?>
					</select>
				<? } ?>
				
			</div>
		</div>
	</div>

	

	<?
}

// include file with arguements
function rcf_get_view( $file = '', $args = array() ) {
	$path = RCF()->directory."/views/".$file;
	
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
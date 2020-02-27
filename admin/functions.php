<?
function render_admin_title($title) {
	// debug($title);
	$link_base = $title["link_base"];
	$id = $title["id"];
	$btn = true;

	if (! isset($id)) {
		$label = $title['plural'];
	} elseif ($id == 0) {
		$label = "Create ".$title['label'];
		$btn = false;
	} elseif ($id > 0) {
		$label = "Edit ".$title['label'];
	}

	?>
	<div class="row content-middle padb2">
		<div class="os-min padr2">
			<h2 class="marg0"><?= $label; ?></h2>
		</div>
		<? if ($btn) { ?>
			<div class="os padl2">
				<a href="<?= "$link_base/edit/0"; ?>" class="btn"><i><?= $title["icon"]; ?></i>New <?= $title["label"]; ?></a>
			</div>
		<? } ?>
	</div>

	<?
}
function render_admin_field($field, $settings) {
	$type = $settings["type"]; 
	$name = $settings['name']; // field_key1231[name]
	$value = $field[$settings["name"]];
	$bind = "";
	if ($settings["bind"]) {
		$bind = " data-bind";
	}
	$required = "";
	if ($settings["required"]) {
		$required = " required";
	}
	if (! $settings["placeholder"]) {
		$settings["placeholder"] = $settings["label"];
	}
	
	?>

	<div class="fieldset <?= $settings["class"]; ?>">
		<label for="<?= $name; ?>"><?= $settings['label']; ?></label>
		<? if ($type == "checkbox") { ?>
			<input type="checkbox" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
		<? } elseif ($type == "number") { ?>
			<input type="number" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
		<? } elseif ($type == "text") { ?>
			<input type="text" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= $value?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
		<? } elseif ($type == "select") { 
			$choices = $settings["choices"];
			?>
			<select name="<?= $name; ?>" id="<?= $name; ?>" class="rcf_dropdown type" <?= $bind; ?> <?= $required; ?>>
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

	

	<?
}
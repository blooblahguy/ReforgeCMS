<?

function admin_page_title() {
	global $request, $options;
	$sitename = $options["sitename"]["value"];
	$page_title = $request["page"]->label_plural;

	echo "Admin | {$page_title} | {$sitename}";
}

function render_admin_menu() {
	global $admin_menu, $PATH;

	list($admin, $controller, $action) = explode("/", $PATH);
	$menu_path = "$admin/$controller";
	if ($action) {
		$menu_path_action = "$admin/$controller/$action";
	}

	// add seperators
	$admin_menu[29] = array("type" => "seperator");
	$admin_menu[69] = array("type" => "seperator");

	$admin_menu = apply_filters("admin/menu", $admin_menu);

	ksort($admin_menu);
	foreach ($admin_menu as $menu) {
		list($admin, $controller, $action) = explode("/", trim($menu["link"], "/"));
		$menu_key = "$admin/$controller";
		if ($action) {
			$menu_key = "$admin/$controller/$action";
		}

		if ($menu["type"]) { 
			echo "<span></span>"; 
		} else { ?>
			<a href="<?= $menu["link"]; ?>" <? if ($menu_key == $menu_path || $menu_key == $menu_path_action) { echo 'class="active"'; }?>><i><?= $menu["icon"]; ?></i> <?= $menu["label"]; ?></a>
		<? }
	}
}

function render_admin_title($title) {
	global $PATH;

	$link_base = ltrim($title["link"], "/");
	$id = $title["id"];
	$btn = true;

	if ($link_base == $PATH) {
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
				<a href="<?= "{$title["link"]}/edit/0"; ?>" class="btn"><i><?= $title["icon"]; ?></i>New <?= $title["label"]; ?></a>
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
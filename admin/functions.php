<?

function admin_page_title() {
	global $request, $options;
	$sitename = $options["sitename"];
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
			<h1 class="marg0"><?= $label; ?></h1>
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
	$name = $settings["name"];
	$value = is_string($field) ? $field : $field[$settings["name"]];

	// $value = ;
	$bind = "";
	if ($settings["bind"]) {
		$bind = " data-bind";
	}
	$required = "";
	if ($settings["required"]) {
		$required = " required";
	}
	$class = "";
	if ($settings["class"]) {
		$class = $settings["class"];
	}
	if (! $settings["placeholder"]) {
		$settings["placeholder"] = $settings["label"];
	}

	$layout = " os-12";
	if ($settings["layout"]) {
		$layout = " ".$settings["layout"];
	}

	$layout = "horizontal";
	if ($settings['layout']) {
		$layout = $settings['layout'];
	}

	$label_size = "os-1 pad1";
	if ($layout == "vertical") {
		$label_size = "os-12";

	}
	
	?>

	<div class="fieldset <?= $class.$layout; ?>">
		<div class="row content-middle">
			<?if ($settings['label']) { ?>
			<div class="<?= $label_size; ?>">
				<label for="<?= $name; ?>"><?= $settings['label']; ?></label>
			</div>

			<? } ?>
			<div class="os">
				<? if ($type == "checkbox") {
					$checked = (int) $value == 1 ? "checked" : ""; ?>
					<input type="checkbox" name="<?= $name; ?>" id="<?= $name; ?>" value="1" class="<?= $class; ?>" <?= $checked; ?> placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
				<? } elseif ($type == "file") { ?>
					<input type="file" name="<?= $name; ?>" id="<?= $name; ?>" class="<?= $class; ?>" <?= $required; ?>>
				<? } elseif ($type == "number") { ?>
					<input type="number" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= $value?>" class="<?= $class; ?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
				<? } elseif ($type == "text") { ?>
					<input type="text" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= $value?>" class="<?= $class; ?>" placeholder="<?= $settings["placeholder"]; ?>" <?= $bind; ?> <?= $required; ?>>
				<? } elseif ($type == "select") { 
					$choices = $settings["choices"];
					?>
					<select name="<?= $name; ?>" id="<?= $name; ?>" class="type <?= $class; ?>" <?= $bind; ?> <?= $required; ?>>
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
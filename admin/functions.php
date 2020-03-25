<?
function admin_page_title() {
	global $request, $options;
	$sitename = $options["sitename"];
	$page_title = $request["page"]->label_plural;

	echo "Admin | {$page_title} | {$sitename}";
}

function render_admin_menu() {
	global $admin_menu;

	$request = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

	$info = explode("/", $request, 4);
	$info1 = array_slice($info, 0, 2);
	$info2 = array_slice($info, 0, 3);
	$path1 = "/".implode("/", $info1);
	$path2 = "/".implode("/", $info2);

	// add seperators
	$admin_menu[] = array("order" => 29, "type" => "seperator");
	$admin_menu[] = array("order" => 69, "type" => "seperator");
	$admin_menu = apply_filters("admin/menu", $admin_menu);

	usort($admin_menu, function($a, $b) {
		return $a['order'] > $b['order'];
	});

	foreach ($admin_menu as $menu) {
		// seperator
		if ($menu['type'] == "seperator") {
			echo '<div class="pad1"></div>';
			continue;
		}

		// build classes
		$class = array();
		if ($menu['link'] == $path1) {
			$class[] = "active";
		}
		if ($menu['link'] == $path2) {
			$class[] = "ancestor";
		} else {
			foreach ($menu['children'] as $c) {
				if ($c['link'] == $path1) {
					$class[] = "ancestor";
					break;
				}
			}
		}
		$class = implode(" ", $class);

		?>
		<div class="menu-item <?= $class; ?>">
			<a href="<?= $menu["link"]; ?>" class="<?= $class; ?>"><i><?= $menu["icon"]; ?></i> <?= $menu["label"]; ?></a>
			
			<? if (count($menu['children'])) { ?>
				<div class="sub-menu">
					<a href="<?= $menu["link"]; ?>" class="<?= $class; ?>"><?= $menu["label"]; ?></a>
					<? foreach ($menu['children'] as $child) {
						$class = array();
						if ($child['link'] == $path1) {
							$class[] = "active";
						}
						if ($child['link'] == $path2) {
							$class[] = "ancestor";
						}
						$class = implode(" ", $class);
						?>
						<a href="<?= $child["link"]; ?>" class="<?= $class; ?>"><?= $child["label"]; ?></a>
						<?
					} ?>
				
				</div>
				
			<? } ?>
		</div>
		<?
	}
}

function remove_template() {

}

function display_results_table($rs, $fields) {
	
	?>
	<table>
		<thead>
			<tr>
				<? foreach ($fields as $k => $f) { ?>
					<th class="<?= $k." ".$f["class"]; ?>"><?= $f["label"]; ?></th>
				<? } ?>
			</tr>
		</thead>
		<tbody>
			<? foreach ($rs as $k => $r) { ?>
				<tr>
					<? 
					foreach ($fields as $i => $f) {
						$val = isset($r[$i]) ? $r[$i] : $i;
						?>
						<td class="<?= $i." ".$f["class"]; ?>">
							<? if ($f["calculate"]) {
								echo $f["calculate"]($val, $r["id"]);
							} elseif ($f["html"]) {
								echo sprintf($f["html"], $val, $r["id"]);
							} else {
								echo $r[$i]; 
							} ?>
						</td>
					<? } ?>
				</tr>
			<? } ?>
		</tbody>
	</table>
<? }

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
	$label = $settings['label'];
	$choices = $settings['choices'];
	$instructions = $settings['instructions'];
	$layout = isset($settings["layout"]) ? isset($settings["layout"]) : "os";
	$value = is_string($field) ? $field : $field[$settings["name"]];
	$unset = array("class", "default", "placeholder", "layout", "type", "choices", "instructions");

	// set up defaults in the attrs array
	$attrs = array();
	$attrs['id'] = $settings['name'];
	$attrs['value'] = $value == "" && isset($settings['default']) ? $settings['default'] : $value;
	$value = $attrs['value'];
	$attrs['class'] = isset($settings['class']) ? $settings['class'] : "";
	$attrs['placeholder'] = isset($settings["placeholder"]) ? $settings["placeholder"] : $settings["label"];

	// unset things we don't want showing up on the input class
	foreach ($unset as $k => $v) {unset($settings[$v]); }

	if ($type == "wysiwyg") {
		$attrs['class'] .= " wysiwyg";
	} elseif ($type == "checkbox") {
		$attrs['checked'] = (int) $attrs['value'] == 1 ? true : false;
	}

	// now build input attribute string
	$attrs_array = array_merge($attrs, $settings);
	$attrs = array_map(function($key, $value) {
		if (gettype($value) == "boolean" && $value === true) {
			return $key;
		} elseif (gettype($value) == "string") {
			return $key.'="'.$value.'"';
		}
	}, array_keys($attrs_array), array_values($attrs_array));
	$attrs = implode(' ', $attrs);

	?>

	<div class="fieldset pady1 <?= $layout; ?>">
		<div class="field_label">
			<? if ($label) { ?>
				<label for="<?= $name; ?>"><?= $label; ?></label>
			<? } ?>
			<? if ($instructions) { ?>
				<div class="field_instructions muted em"><?= $instructions; ?></div>
			<? } ?>
		</div>
		<div class="field_value">
			<? if ($type == "select") { ?>
				<select <?= $attrs; ?>>
					<option value="" disabled selected>--Select</option>
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

			<? } elseif ($type == "textarea") { ?>
				<textarea type="text" rows="5" <?= $attrs; ?>><?= $attrs_array['value']; ?></textarea>
			<? } elseif ($type == "wysiwyg") { ?>
				<input type="hidden" class="wysiwyg_input" name="<?= $name; ?>" value="<?= $attrs_array['value']; ?>">
				<div <?= $attrs; ?>><?= htmlspecialchars_decode($attrs_array['value']); ?></div>
			<? } else { ?>
				<input type="<?= $type; ?>" <?= $attrs; ?>>
			<? } ?>
		</div>
	</div>


	<?
}
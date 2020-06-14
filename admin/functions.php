<?

/**
 * Add arbitrary links to the admin menu, with parenting
 */
function add_admin_menu($info) {
	global $admin_menu;

	$info = array_merge(array(
		"label" => "Menu Item",
		"link" => "/admin",
		"order" => "30",
		"icon" => "30",
		"parent" => false,
		),
		$info
	);

	$admin_menu[] = $info;
}


function admin_head() {
	global $request, $options;

	$favicon = "data:image/png;base64,";
	$favicon .= "iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwMS8yMy8xNiCnhC4AAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACZ0lEQVRIib2XTWgUMRTHfy0VVBAUFQ8WhIJKbaMBRVhdtCIoKPSmgpfgzaMHUfzAKoJYd/HgpYgoBvyAHrwJBRELFimiEs1BvCz25qGKliqI1elhMnScTmZ3p519EObl/V/mR5K3mWxbEATkNS3kBmCpsuZTs2Pbc1NDOwWU8wzMDdZCrnXgPS0FA5fds6yFbGsJWAvZBZx03S5gS0vAwLXE2P2Fg7WQvcCxRPhg4WDgekqspIVcVhhYC7kTOJwirQL6CgOTPtvI1hUC1kKWgX0euQY8LAQM3MjQLipr/iw6WAt5CCh5ZKuseezy+hcVDAxmaOcctBu412h11wVrIY8CvR75rbLmqfOvAKuBngWD3Rl8NSMlmu1W4IiL7V0wGDgBbPJoL5U1z5xficUbOj69YC1kB+Hy+ey0y9sFHIjFS1rIFbnBgAI6PdqIsua185OFt5Jwr5sHayE7gTMZ4866vD7m30AeKWs+5wID5/Hv7bCy5oPzKwltBrhQD5oFHga+p8T/MlfJ/cCOhH63kdl6wcqaUeBjivRAWVNzh0Tyg/GbuetQXevI0Mb4/5icAQacvx7oTuQPKWu+aCE3A8cJV+yOsmY67eVZVf080b+trJlwfs21yKaBAS1kD/AOuATcBEa0kGuaBY8Dv5z/k9gyKmv+AaOx3IqyZoqw2pfH4rvxfMO9YGXND+CN61aVNZOJlGhFJoGq89O2bklTYGdjhHtbTdFeueegsiZamVtA/D/Re8Kf5jzLKi6AF8A3T4FMAE+AoSigrBnXQm4nvIVOEdbF19Q3B0Hgbfd7t7XX0Tdm6VltFjtf+JlxpZ4PAAAAAElFTkSuQmCC";

	echo '<meta name="robots" content="noindex" />';

	$parts = array();
	$seperator = $options['seo_seperator'];
	$sitename = $options['sitename'];
	$title = $request['page_title'];
	$category = "Admin";

	$parts[] = $title;
	if ($category != '') {
		$parts[] = $category;
	}
	$parts[] = $sitename;

	$title = implode(" $seperator ", $parts);

	// Title and description
	echo "<title>$title</title>";

	// echo out SEO
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name=viewport content="width=device-width, initial-scale=1">';

	// favicon
	echo '<link rel="shortcut icon" href="'.$favicon.'" type="image/x-icon" />';

	// styles
	rf_styles();
}

function render_admin_menu() {
	global $admin_menu;

	$request = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

	$info = explode("/", $request, 4);
	$info1 = array_slice($info, 0, 2);
	$info2 = array_slice($info, 0, 3);
	$path = "/".implode("/", $info);
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
			$class[] = "active idk";
		}
		if ($menu['link'] == $path2) {
			$class[] = "ancestor idk";
		} else {
			foreach ($menu['children'] as $c) {
				if ($c['link'] == $path) {
					$class[] = "active";
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
								echo $f["calculate"]($val, $r);
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

function render_admin_header($title) { ?>
	<div class="row content-middle padb2">
		<div class="os-min padr2">
			<h2 class="marg0"><?= $title; ?></h1>
		</div>
	</div>
	<?
} 

function render_admin_title($title, $edit = true) {
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

	if (! $edit) {
		$btn = false;
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
<? $menu = array(
	0 => array(
		"label" => "Dashboard",
		"icon" => "speed",
		"link" => "",
	),

	// Leave gap for custom post types

	// common usage areas
	29 => array(
		"type" => "seperator"
	),
	30 => array(
		"label" => "Users",
		"icon" => "account_circle",
		"link" => "users",
		"permission" => "manage_users",
	),
	35 => array(
		"label" => "Roles",
		"icon" => "how_to_reg",
		"link" => "roles",
		"permission" => "manage_roles",
	),
	40 => array(
		"label" => "Comments",
		"icon" => "forums",
		"link" => "comments",
		"permission" => "manage_comments",
	),
	45 => array(
		"label" => "Forms",
		"icon" => "message",
		"link" => "forms",
		"permission" => "manage_forms",
	),
	50 => array(
		"label" => "Menus",
		"icon" => "menu",
		"link" => "menus",
		"permission" => "manage_menus",
	),

	// configuration
	69 => array(
		"type" => "seperator"
	),
	70 => array(
		"label" => "Custom Post Types",
		"icon" => "web",
		"link" => "post_types",
		"permission" => "manage_post_types",
	),
	75 => array(
		"label" => "Custom Fields",
		"icon" => "filter_list",
		"link" => "custom_fields",
		"permission" => "manage_custom_fields",
	),
	80 => array(
		"label" => "Widgets",
		"icon" => "widgets",
		"link" => "widgets",
		"permission" => "manage_widgets",
	),
	85 => array(
		"label" => "Settings",
		"icon" => "settings",
		"link" => "settings",
		"permission" => "manage_settings",
	),
); 
$cpts = $db->exec("SELECT * FROM post_types WHERE admin_menu = 1 ORDER BY `order` ASC");
// if (count($cpts) > 0 ) {$menu[4] = array("type" => "seperator"); }
foreach ($cpts as $post) {
	// debug($post);
	$menu[5 + $post["order"]] = array(
		"label" => $post["label_plural"],
		"icon" => $post["icon"],
		"link" => "posts/".$post['slug'],
		"permission" => array("update_any_{$post['slug']}", "update_own_{$post['slug']}")
	)
	?>
<? } 
ksort($menu);
?>


<!DOCTYPE html>
<html lang="en" class="h100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin</title>
	<link rel="stylesheet" href="/admin/css/dist/style.php">
	<link rel="shortcut icon" href="/content/img/favicon.png" type="image/x-icon" />
</head>
<body class="h100">
	<div class="wrapper h100">
		<div class="row h100">
			<div class="os-min leftsidebar bg-black">
				<? 
				foreach ($menu as $menu) { ?>
					<? if ($menu["type"]) { echo "<span></span>"; } else {
						if (isset($menu["permission"]) &! $user->can($menu["permission"])) { continue; } ?>
						<a href="/admin/<?= $menu["link"]; ?>" <? if ($menu["link"] == $MENU || $menu["link"] == $MENUPAIR) { echo 'class="active"'; }?>><i><?= $menu["icon"]; ?></i> <?= $menu["label"]; ?></a>
					<? } ?>
				<? } ?>
				
			</div>
			<div class="os content_outer">
				<div class="top_outer text-right pad1 bg-black">
					<a href="/" class="view padx2 pady1">View Website</a>
					<a href="/admin/logout" class="self-end padx2 pady1">Logout</a>
				</div>
				<div class="content_inner pad2 padb4">
					<? display_alerts(); ?>
				
			
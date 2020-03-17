<?
$active_theme = get_option('active_theme');
$admin_theme = get_option("admin_theme");
$theme = "theme_$admin_theme";
if ($admin_theme == "dark") {
	$theme .= " bg-black";
}
?>
<!DOCTYPE html>
<html lang="en" class="h100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= admin_page_title(); ?></title>
	<link rel="stylesheet" href="/admin/css/dist/style.php">
	<link rel="shortcut icon" href="/content/themes/<?= $active_theme; ?>/img/favicon.png" type="image/x-icon" />

	<? rf_styles(); ?>
</head>
<body class="h100">
	<div class="wrapper h100 <?= $theme; ?>">
		<div class="row h100">
			<div class="os-min leftsidebar bg-black">
				<? render_admin_menu(); ?>
			</div>
			<div class="os content_outer">
				<div class="top_outer text-right pad1 bg-black">
					<a href="/admin/clear-cache" class="view padx2 pady1">Clear Cache</a>
					<a href="/" class="view padx2 pady1">View Website</a>
					<a href="/logout" class="self-end padx2 pady1">Logout</a>
				</div>
				<? display_alerts(); ?>
				<div class="content_inner pad2 padb4 <?= $theme; ?>">
				
			
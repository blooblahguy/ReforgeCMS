<?
$active_theme = get_option('active_theme');
$admin_theme = current_user()->admin_theme;
if (! $admin_theme) { $admin_theme = "default"; }
?>
<!DOCTYPE html>
<html lang="en" class="h100">
<head>
	<? admin_head(); ?>

	<link rel="stylesheet" href="/admin/css/dist/style.php?theme=<?= $admin_theme; ?>">
	<script src="/core/js/sortable.min.js"></script>
</head>
<body class="h100">
	<div class="wrapper h100">
		<div class="row h100">
			<div class="os-min leftsidebar bg-black">
				<? render_admin_menu(); ?>
			</div>
			<div class="os content_outer">
				<div class="top_outer text-right pad1 bg-black">
					<? if (current_user()->can("administrator")) { ?>
						<a href="/admin/clear-cache" class="view padx2 pady1">Clear Cache</a>
					<? } ?>
					<a href="/admin/users/edit/<?= current_user()->id; ?>" class="view padx2 pady1">Profile</a>
					<a href="/" class="view padx2 pady1">View Website</a>
					<a href="/logout" class="self-end padx2 pady1">Logout</a>
				</div>
				<? display_alerts(); ?>
				<div class="content_inner pad2 padb4 <?= $theme; ?>">
					<div class="page_content">
				
			
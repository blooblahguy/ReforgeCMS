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
				<? render_admin_menu(); ?>
			</div>
			<div class="os content_outer">
				<div class="top_outer text-right pad1 bg-black">
					<a href="/" class="view padx2 pady1">View Website</a>
					<a href="/admin/logout" class="self-end padx2 pady1">Logout</a>
				</div>
				<div class="content_inner pad2 padb4">
					<? display_alerts(); ?>
				
			
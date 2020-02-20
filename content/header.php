
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6">
<![endif]-->
<!--[if IE 7]>
<html id="ie7">
<![endif]-->
<!--[if IE 8]>
<html id="ie8">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html>
<!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">  -->

	<title>Big Dumb Gaming</title>

	<meta name="theme-color" content="#171C23">
	<meta name="msapplication-navbutton-color" content="#171C23">
	<meta name="apple-mobile-web-app-status-bar-style" content="#171C23">
	
	<link rel="stylesheet" href="/content/css/dist/style.php"/>

	<link rel="shortcut icon" href="/content/img/favicon.png" type="image/x-icon" />
</head>
<body>
	<div class="wrapper">
		<div class="top_outer bg-dark">
			<div class="top container row content-middle">
				<div class="os-md-min">
					<? $socials = get_field("socials", "options"); ?>
				</div>
				
				<div class="os-12 os-md links hidden md-display-block text-right">
					<? if ($user->logged_in()) { ?>
						<a href="/profile">Profile</a>
						<a href="<?= $core->get("admin_path"); ?>">Admin</a>
						<a href="/logout">Logout</a>
					<? } else { ?>
						<a href="/login">Login</a>
					<? } ?>
				</div>
			</div>
		</div>
		<div class="header_outer bg-dark">
			<div class="header container">
				<div class="row content-middle">
					<h1 class="os-min">
						<a href="/" class="logo"><?= get_file_contents_url("/content/img/emblem.svg"); ?>BIG<span>DUMB</span>GAMING</a>
					</h1>
				</div>
			</div>
		</div>

		<div class="content_outer">
			<div class="content">
			
			
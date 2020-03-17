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
	
	<link rel="stylesheet" href="<?= theme_url(); ?>/css/dist/style.php"/>

	<link rel="shortcut icon" href="<?= theme_url(); ?>/img/favicon.png" type="image/x-icon" />
</head>
<body>
	<div class="wrapper">
		<div class="top_outer bg-dark">
			<div class="top container row content-middle">
				<div class="os-md-min">
					<? $socials = get_field("socials", "options"); ?>
				</div>
				
				<div class="os-12 os-md links hidden md-display-block text-right">
					<? if (logged_in()) { ?>
						<a href="/profile">Profile</a>
						<? if ($user->can("access_admin")) { ?>
							<a href="/admin">Admin</a>
						<? } ?>
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
					<h1 class="os-min marg0">
						<a href="/" class="logo"><?= get_file_contents_url(theme_url()."/img/emblem.svg"); ?>BIG<span>DUMB</span>GAMING</a>
					</h1>
					<?
					$menu = array();
					$menu[] = array(
						"label" => "News",
						"link" => "/",
					);
					$menu[] = array(
						"label" => "Raid Guides",
						"link" => "/raid-guides",
					);
					$menu[] = array(
						"label" => "About Us",
						"link" => "/about-us",
					);
					$menu[] = array(
						"label" => "Players",
						"link" => "/players",
					);
					?>
					<ul class="os menu text-right">
						<? foreach ($menu as $link) { ?>
							<li class="link">
								<a href="<?= $link['link']; ?>"><?= $link['label']; ?></a>
							</li>
						<? } ?>
					</ul>
					<div class="os-min">
						<a href="/apply" class="apply btn">Apply</a>
					</div>
				</div>
			</div>
		</div>

		<div class="content_outer">
			<div class="content">
				<? display_alerts(); ?>
			
			
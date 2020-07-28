<?
// $b64image = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].theme_url()."/img/bdgg.svg"));
// debug($b64image);

// $favicon = "data:image/png;base64,";
// $favicon .= "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjxzdmcgdmlld0JveD0iMTIuNjYzIDEyLjY2MyA0NjQgNDY0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiNlMGIxNWEiPg0KICA8ZyBpZD0iTGF5ZXJfMiIgdHJhbnNmb3JtPSJtYXRyaXgoMSwgMCwgMCwgMSwgLTI1NS4zMzY2MDksIC0yNTUuMzM2NjA5KSI+DQogICAgPGc+DQogICAgICA8Zz4NCiAgICAgICAgPHBhdGggY2xhc3M9InN0MiIgZD0iTTQ5Ni4wNSwzODEuNDZsMTQuMTksNTEuNDRMNDI3LjksNTg4LjIzbC0xNC4yLTUxLjYyTDQ5Ni4wNSwzODEuNDYgTTQ5OS4zLDM1MS44N2wtOTcuMzcsMTgzLjQ0bDIyLjcsODIuNTUgTDUyMiw0MzQuMThMNDk5LjMsMzUxLjg3TDQ5OS4zLDM1MS44N3oiLz4NCiAgICAgIDwvZz4NCiAgICAgIDxnPg0KICAgICAgICA8cGF0aCBjbGFzcz0ic3QyIiBkPSJNNTI4Ljg1LDQ4OS45OWw2LjU4LDI2LjI4bC0zOC43OSw3Mi43OGwtNi44NC0yNS41N0w1MjguODUsNDg5Ljk5IE01MzIuNTcsNDU5LjU2bC01NC41MSwxMDIuNTlsMTUuMTgsNTYuNyBsNTMuOS0xMDEuMTNMNTMyLjU3LDQ1OS41Nkw1MzIuNTcsNDU5LjU2eiIvPg0KICAgICAgPC9nPg0KICAgICAgPGc+DQogICAgICAgIDxwYXRoIGNsYXNzPSJzdDIiIGQ9Ik01NTMuMzMsNTc0Ljg0YzYuOTksMCwxMi42Nyw1LjY5LDEyLjY3LDEyLjY3cy01LjY5LDEyLjY3LTEyLjY3LDEyLjY3cy0xMi42Ny01LjY5LTEyLjY3LTEyLjY3IFM1NDYuMzQsNTc0Ljg0LDU1My4zMyw1NzQuODQgTTU1My4zMyw1NjMuODRjLTEzLjA3LDAtMjMuNjcsMTAuNi0yMy42NywyMy42N2MwLDEzLjA3LDEwLjYsMjMuNjcsMjMuNjcsMjMuNjcgYzEzLjA3LDAsMjMuNjctMTAuNiwyMy42Ny0yMy42N0M1NzcsNTc0LjQ0LDU2Ni40LDU2My44NCw1NTMuMzMsNTYzLjg0TDU1My4zMyw1NjMuODR6Ii8+DQogICAgICA8L2c+DQogICAgPC9nPg0KICAgIDxnPg0KICAgICAgPHBhdGggY2xhc3M9InN0MiIgZD0iTTUwMCwyNzljMjkuODQsMCw1OC43OCw1Ljg0LDg2LjAyLDE3LjM2YzI2LjMyLDExLjEzLDQ5Ljk1LDI3LjA3LDcwLjI1LDQ3LjM3IGMyMC4zLDIwLjMsMzYuMjQsNDMuOTQsNDcuMzcsNzAuMjVDNzE1LjE2LDQ0MS4yMiw3MjEsNDcwLjE2LDcyMSw1MDBjMCwyOS44NC01Ljg0LDU4Ljc4LTE3LjM2LDg2LjAyIGMtMTEuMTMsMjYuMzItMjcuMDcsNDkuOTUtNDcuMzcsNzAuMjVjLTIwLjMsMjAuMy00My45NCwzNi4yNC03MC4yNSw0Ny4zN0M1NTguNzgsNzE1LjE2LDUyOS44NCw3MjEsNTAwLDcyMSBjLTI5Ljg0LDAtNTguNzgtNS44NC04Ni4wMi0xNy4zNmMtMjYuMzItMTEuMTMtNDkuOTUtMjcuMDctNzAuMjUtNDcuMzdjLTIwLjMtMjAuMy0zNi4yNC00My45NC00Ny4zNy03MC4yNSBDMjg0Ljg0LDU1OC43OCwyNzksNTI5Ljg0LDI3OSw1MDBjMC0yOS44NCw1Ljg0LTU4Ljc4LDE3LjM2LTg2LjAyYzExLjEzLTI2LjMyLDI3LjA3LTQ5Ljk1LDQ3LjM3LTcwLjI1IGMyMC4zLTIwLjMsNDMuOTQtMzYuMjQsNzAuMjUtNDcuMzdDNDQxLjIyLDI4NC44NCw0NzAuMTYsMjc5LDUwMCwyNzkgTTUwMCw2OTIuNzljMTA2LjMxLDAsMTkyLjc5LTg2LjQ5LDE5Mi43OS0xOTIuNzkgUzYwNi4zMSwzMDcuMjEsNTAwLDMwNy4yMVMzMDcuMjEsMzkzLjY5LDMwNy4yMSw1MDBTMzkzLjY5LDY5Mi43OSw1MDAsNjkyLjc5IE01MDAsMjY4Yy0xMjguMTMsMC0yMzIsMTAzLjg3LTIzMiwyMzIgczEwMy44NywyMzIsMjMyLDIzMnMyMzItMTAzLjg3LDIzMi0yMzJTNjI4LjEzLDI2OCw1MDAsMjY4TDUwMCwyNjh6IE01MDAsNjgxLjc5Yy0xMDAuNCwwLTE4MS43OS04MS4zOS0xODEuNzktMTgxLjc5IGMwLTEwMC40LDgxLjM5LTE4MS43OSwxODEuNzktMTgxLjc5UzY4MS43OSwzOTkuNiw2ODEuNzksNTAwQzY4MS43OSw2MDAuNCw2MDAuNCw2ODEuNzksNTAwLDY4MS43OUw1MDAsNjgxLjc5eiIvPg0KICAgIDwvZz4NCiAgPC9nPg0KPC9zdmc+";
?>

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

	<? rf_head(); ?>

	<meta name="theme-color" content="#171C23">
	<meta name="msapplication-navbutton-color" content="#171C23">
	<meta name="apple-mobile-web-app-status-bar-style" content="#171C23">
	
	<link rel="stylesheet" href="<?= theme_url(); ?>/css/dist/style.php"/>

	<link rel="shortcut icon" href="<?= theme_url(); ?>/img/bdgg_emblem.png" type="image/x-icon" />

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-78277409-1', 'auto');
		ga('send', 'pageview');
	</script>

	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
</head>
<body <?= body_classes(); ?> lang="en">
	<div class="wrapper">
		<div class="top_outer">
			<div class="top container row content-middle">
				<div class="os-md-min">
					<? render_socials(); ?>
				</div>
				
				<div class="os-12 os-md links hidden md-display-block text-right">
					<? if (logged_in()) { ?>
						<a href="/profile"><?= $user->username; ?></a>
						<? if ($user->can("access_admin")) { ?>
							<a href="/admin">Admin</a>
						<? } ?>
						<a href="/logout">Logout</a>
					<? } else { ?>
						<a href="/login">Login</a>
						<a href="/register">Register</a>
					<? } ?>
				</div>
			</div>
		</div>
		<div class="header_outer bg-dark">
			<div class="header container">
				<div class="row content-middle">
					<h1 class="os-min marg0">
						<a href="/" class="logo"><?= get_file_contents_url(theme_url()."/img/bdgg.svg"); ?>BD<span>GG</span></a>
					</h1>
					<?
					$menu = get_menu("main-menu");
					$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
					?>
					<ul class="os menu text-right">
						<? foreach ($menu as $link) { ?>
							<li class="link">
								<?= $link['html']; ?>
							</li>
						<? } ?>
						<?
						if ($user->can("view_applications")) { ?>
							<li class="link">
								<a href="/recruitment/applications" class="<? if (strpos($path, "recruitment/applications") !== false) {echo "active"; } ?>">Applications</a>
								<!-- todo: show updated applications # -->
							</li>
						<? } elseif (logged_in()) { ?>
							<li class="link">
								<a href="/recruitment/applications" class="<? if (strpos($path, "recruitment/apply") !== false) {echo "active"; } ?>">Apply</a>
							</li>
						<? } else { ?>
							<li class="link">
								<a href="/recruitment" class="<? if (strpos($path, "recruitment") !== false) {echo "active"; } ?>">Apply</a>
							</li>
						<? } ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="content_outer">
			<div class="content">
				<? display_alerts(); ?>
			
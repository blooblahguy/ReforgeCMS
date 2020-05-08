<?
// $b64image = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].theme_url()."/img/favicon.png"));
// debug($b64image);

$favicon = "data:image/png;base64,";
$favicon .= "iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwMS8yMy8xNiCnhC4AAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACZ0lEQVRIib2XTWgUMRTHfy0VVBAUFQ8WhIJKbaMBRVhdtCIoKPSmgpfgzaMHUfzAKoJYd/HgpYgoBvyAHrwJBRELFimiEs1BvCz25qGKliqI1elhMnScTmZ3p519EObl/V/mR5K3mWxbEATkNS3kBmCpsuZTs2Pbc1NDOwWU8wzMDdZCrnXgPS0FA5fds6yFbGsJWAvZBZx03S5gS0vAwLXE2P2Fg7WQvcCxRPhg4WDgekqspIVcVhhYC7kTOJwirQL6CgOTPtvI1hUC1kKWgX0euQY8LAQM3MjQLipr/iw6WAt5CCh5ZKuseezy+hcVDAxmaOcctBu412h11wVrIY8CvR75rbLmqfOvAKuBngWD3Rl8NSMlmu1W4IiL7V0wGDgBbPJoL5U1z5xficUbOj69YC1kB+Hy+ey0y9sFHIjFS1rIFbnBgAI6PdqIsua185OFt5Jwr5sHayE7gTMZ4866vD7m30AeKWs+5wID5/Hv7bCy5oPzKwltBrhQD5oFHga+p8T/MlfJ/cCOhH63kdl6wcqaUeBjivRAWVNzh0Tyg/GbuetQXevI0Mb4/5icAQacvx7oTuQPKWu+aCE3A8cJV+yOsmY67eVZVf080b+trJlwfs21yKaBAS1kD/AOuATcBEa0kGuaBY8Dv5z/k9gyKmv+AaOx3IqyZoqw2pfH4rvxfMO9YGXND+CN61aVNZOJlGhFJoGq89O2bklTYGdjhHtbTdFeueegsiZamVtA/D/Re8Kf5jzLKi6AF8A3T4FMAE+AoSigrBnXQm4nvIVOEdbF19Q3B0Hgbfd7t7XX0Tdm6VltFjtf+JlxpZ4PAAAAAElFTkSuQmCC";
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

	<link rel="shortcut icon" href="<?= $favicon; ?>" type="image/x-icon" />
</head>
<body <?= body_classes(); ?>>
	<div class="wrapper">
		<div class="top_outer">
			<div class="top container row content-middle">
				<div class="os-md-min">
					<? render_socials(); ?>
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
					$menu = get_menu("main-menu");
					?>
					<ul class="os menu text-right">
						<? foreach ($menu as $link) { ?>
							<li class="link">
								<?= $link['html']; ?>
							</li>
						<? } ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="content_outer">
			<div class="content">
				<? display_alerts(); ?>
			
			
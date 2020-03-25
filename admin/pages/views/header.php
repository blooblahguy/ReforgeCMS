<?
// $src = $b64image = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/content/themes/{$active_theme}/img/favicon.png"));
// debug($src);
// exit();
$favicon = "data:image/png;base64,";
$favicon .= "iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwMS8yMy8xNiCnhC4AAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACZ0lEQVRIib2XTWgUMRTHfy0VVBAUFQ8WhIJKbaMBRVhdtCIoKPSmgpfgzaMHUfzAKoJYd/HgpYgoBvyAHrwJBRELFimiEs1BvCz25qGKliqI1elhMnScTmZ3p519EObl/V/mR5K3mWxbEATkNS3kBmCpsuZTs2Pbc1NDOwWU8wzMDdZCrnXgPS0FA5fds6yFbGsJWAvZBZx03S5gS0vAwLXE2P2Fg7WQvcCxRPhg4WDgekqspIVcVhhYC7kTOJwirQL6CgOTPtvI1hUC1kKWgX0euQY8LAQM3MjQLipr/iw6WAt5CCh5ZKuseezy+hcVDAxmaOcctBu412h11wVrIY8CvR75rbLmqfOvAKuBngWD3Rl8NSMlmu1W4IiL7V0wGDgBbPJoL5U1z5xficUbOj69YC1kB+Hy+ey0y9sFHIjFS1rIFbnBgAI6PdqIsua185OFt5Jwr5sHayE7gTMZ4866vD7m30AeKWs+5wID5/Hv7bCy5oPzKwltBrhQD5oFHga+p8T/MlfJ/cCOhH63kdl6wcqaUeBjivRAWVNzh0Tyg/GbuetQXevI0Mb4/5icAQacvx7oTuQPKWu+aCE3A8cJV+yOsmY67eVZVf080b+trJlwfs21yKaBAS1kD/AOuATcBEa0kGuaBY8Dv5z/k9gyKmv+AaOx3IqyZoqw2pfH4rvxfMO9YGXND+CN61aVNZOJlGhFJoGq89O2bklTYGdjhHtbTdFeueegsiZamVtA/D/Re8Kf5jzLKi6AF8A3T4FMAE+AoSigrBnXQm4nvIVOEdbF19Q3B0Hgbfd7t7XX0Tdm6VltFjtf+JlxpZ4PAAAAAElFTkSuQmCC";

$active_theme = get_option('active_theme');
$admin_theme = current_user()->admin_theme;
if (! $admin_theme) { $admin_theme = "default"; }
?>
<!DOCTYPE html>
<html lang="en" class="h100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= admin_page_title(); ?></title>
	<link rel="stylesheet" href="/admin/css/dist/style.php?theme=<?= $admin_theme; ?>">
	<link rel="shortcut icon" href="<?= $favicon; ?>" type="image/x-icon" />

	<? rf_styles(); ?>

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
					<a href="/admin/clear-cache" class="view padx2 pady1">Clear Cache</a>
					<a href="/admin/users/edit/<?= current_user()->id; ?>" class="view padx2 pady1">Profile</a>
					<a href="/" class="view padx2 pady1">View Website</a>
					<a href="/logout" class="self-end padx2 pady1">Logout</a>
				</div>
				<? display_alerts(); ?>
				<div class="content_inner pad2 padb4 <?= $theme; ?>">
				
			
<!DOCTYPE html>
<html lang="en" class="h100">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= get_option( "sitename" ) ?> | Login</title>

	<link rel="stylesheet" href="/admin/css/dist/style.php">
</head>

<body class="h100 bg-black">
	<div class="row content-center content-middle h100">
		<?
		display_alerts();
		?>
		<form action="/admin/login" id="recaptcha-form" method="POST" class="os-12 os-md-6 os-lg-3">
			<h1>Login</h1>
			<? do_action( "admin/login/before_form" ); ?>
			<div class="row g1">
				<div class="os-12">
					<label for="">Email</label>
					<input type="text" name="email" required>
				</div>
				<div class="os-12">
					<label for="">Password</label>
					<input type="password" name="password" required>
				</div>
				<input type="hidden" name="redirect" value="/admin">
				<div class="os-12">
					<? //add_recaptcha($label = "Submit"); ?>
				</div>
				<input type="submit">
			</div>
			<? do_action( "admin/login/after_form" ); ?>
		</form>
	</div>
	<? rf_scripts(); ?>
</body>

</html>
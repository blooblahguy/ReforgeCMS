<!DOCTYPE html>
<html lang="en" class="h100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Setup New Website</title>

	<link rel="stylesheet" href="/rf_admin/css/dist/style.php">
</head>
<body class="h100 bg-black">
	<div class="row content-center content-middle h100">
		<form action="/setup" method="POST" class="os-12 os-md-8 os-lg-4">
			<? display_alerts(); ?>
			<h1>Reforge Setup</h1>
			<div class="row g1">
				<div class="os-12">
					<label for="">Site Name</label>
					<input type="text" name="sitename" required>
				</div>
				<div class="os-12">
					<label for="">Admin Username</label>
					<input type="text" name="username" required>
				</div>
				<div class="os-12">
					<label for="">Admin Email</label>
					<input type="text" name="email" required>
				</div>
				<div class="os-6">
					<label for="">Admin Password</label>
					<input type="password" name="password" required>
				</div>
				<div class="os-6">
					<label for="">Admin Password Confirm</label>
					<input type="password" name="password_confirm" required>
				</div>
				<div class="os-12">
					<input type="submit">
				</div>
			</div>
		</form>
	</div>
</body>
</html>
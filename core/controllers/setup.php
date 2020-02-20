<?
	class Setup {
		static function process($core, $args) {
			global $alert;

			$audit = \Audit::instance();

			$sitename = $_POST['sitename'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$password_confirm = $_POST['password_confirm'];

			$fail = false;

			if (! $audit->email($email, false)) {
				$alert->error("Invalid Email Address");
				$fail = true;
			}
			if ($password != $password_confirm) {
				$alert->error("Password do not match");
				$fail = true;
			}

			if ($fail) {
				redirect("/");
			}

			$crypt = \Bcrypt::instance();
			$password = $crypt->hash($password, $core->get("secret"));

			$user = new User();
			$user->username = $username;
			$user->email = $email;
			$user->password = $password;
			$user->save();

			$option = new Option();
			$option->key = "sitename";
			$option->value = $sitename;
			$option->save();

			$alert->message("First setup successful");
			redirect("/admin");
		}
	}
?>
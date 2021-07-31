<?php
	class Setup extends \Prefab {
		function index() {
			global $root;
			require $root."/core/setup.php";
		}

		function process($core, $args) {
			$alert = \Alerts::instance();
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


			// set defaults
			$role = new Role();
			$role->slug = "administrator";
			$role->label = "Administrator";
			$role->priority = 0;
			$role->locked = 1;
			$role->permissions = serialize(array("administrator"));
			$role->save();

			// post types
			$page = new PostType();
			$page->slug = "page";
			$page->label = "Page";
			$page->label_plural = "Pages";
			$page->public = 1;
			$page->admin_menu = 1;
			$page->icon = "layers";
			$page->order = 2;
			$page->statuses = 'a:1:{i:0;a:3:{s:4:"name";s:5:"test2";s:6:"status";s:5:"Draft";s:14:"default_status";i:1;}}';
			$page->save();

			$user = new User();
			$user->username = $username;
			$user->email = $email;
			$user->password = $password;
			$user->role_id = $role->id;
			$user->save();

			// default options
			set_option("sitename", $sitename);
			set_option("active_theme", "reforge2020");
			set_option("active-plugins", serialize(array()));

			$alert->message("First setup successful");
			redirect("/admin");
		}
	}

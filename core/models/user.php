<?

class User extends \RF\Mapper {
	private 
		$token,
		$logged_in = false,
		$permissions;

	function __construct() {
		$schema = array(
			"username" => array(
				"type" => "VARCHAR(256)"
			), 
			"email" => array(
				"type" => "VARCHAR(190)",
				"unique" => true,
			),
			"password" => array(
				"type" => "VARCHAR(256)"
			),
			"role_id" => array(
				"type" => "INT(7)"
			),
			"class" => array(
				"type" => "VARCHAR(256)"
			),
			"twitch" => array(
				"type" => "VARCHAR(256)"
			),
			"verified" => array(
				"type" => "INT(1)",
				"attrs" => "DEFAULT 0",
			),
			"avatar" => array(
				"type" => "VARCHAR(256)"
			),
			"admin_theme" => array(
				"type" => "VARCHAR(100)"
			),
			"last_login" => array(
				"type" => "DATETIME",
			),
			"modified" => false
		);

		parent::__construct("rf_users", $schema);
	}

	function afterinsert() {
		$this->check_avatar();
	}

	function get_user(int $id = 0) {
		if ($id === 0) {
			if ($this->remembered()) {
				$this->load("*", array("id = :id", ":id" => $this->id));
				$this->reup_login();
			}
		} else {
			$this->load("*", array("id = :id", ":id" => $id));
		}
	}

	function render_avatar() {
		echo "<div class='user_avatar'><img src='{$this->avatar}' class='preview_{$this->username}' alt='{$this->username}'></div>";
	}

	function mimic() {
		$this->logged_in = true;
	}

	function check_avatar() {
		global $root;
		if (! $this->id) { return; }
		if ($this->avatar == "" || ! file_exists($root.$this->avatar)) {
			$img = rand(1, 7);
			$img = "/core/assets/img/avatar_$img.png";
			$this->avatar = $img;
			$this->update();
		}
	}


	function logout() {
		// clear session
		session()->clear("user_id");
		session()->clear("token");
		session()->clear("reup_date");

		// clear cookie
		setcookie('logincookie', "", -1, "/");

		// clear database
		$lc = new LoginCookie();
		$lc->load("*", array("token = :token AND user_id = :user_id", ":token" => $this->token, ":user_id" => $this->id));
		if ($lc->id > 0) {
			$lc->erase();
		}

		\Alerts::instance()->success("Logged out");
		redirect("/");
	}

	function login() {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = $this->load("*", array("email = :email", ":email" => $email));
		if ($user && password_verify($password, $user->password)) {
			// store this login
			$token = password_hash(random_bytes(32).time(), PASSWORD_DEFAULT);
			$this->token = $token;
			$this->reup_login();

			// now log them in
			$user->last_login = "NOW()";
			$user->save();

			// delete old cookies
			$lc = new LoginCookie();
			$lc->query("DELETE FROM {$lc->table} WHERE modified < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))");
			return true;
		}

		return false;
	}

	/**
	 * Reups user's login cookie/session
	 */
	private function reup_login() {
		if (! $this->id || ! $this->token) { return false; }
		$today = strtotime(Date("ymd"));
		$last = session()->get('reup_date');

		// up session
		session()->set('user_id', $this->id);
		session()->set('token', $this->token);
		session()->set('reup_date', strtotime(Date("ymd")));
		
		// up cookie
		setcookie('logincookie', $this->id.":".$this->token, time() + 60 * 60 * 24 * 30, "/"); // reup the cookie (30 days)

		// up database
		// don't reup more than once a day
		if ($last && $last == $today) {
			return;
		}

		$lc = new LoginCookie();
		$lc->load("*", array("token = :token AND user_id = :user_id", ":token" => $this->token, ":user_id" => $this->id));

		if (! $lc->id || time() != strtotime($lc->modified)) {
			$lc->user_id = $this->id;
			$lc->token = $this->token;

			$lc->save();
		}

		// Only allow 3 devices
		$stored = $lc->find("*", array("user_id = :user_id", ":user_id" => $this->id), array(
			"order by" => "modified DESC"
		));
		$stored = array_slice($stored, 3);
		if ($stored && count($stored) > 0) {
			foreach ($stored as $key => $val) {
				$lc->query("DELETE FROM $lc->table WHERE id = {$val['id']}");
			}
		}
	}

	/**
	 * Securely detect if user should be automatically logged back in
	 */
	private function remembered() {
		$lc = new LoginCookie();

		if (session()->get("user_mimic")) {
			return true;
		}

		// shortcut the code if we're good to go
		if ($this->logged_in) { 
			return true;
		}

		// if we have a cookie, log us in if possible
		if (isset($_COOKIE['logincookie'])) {
			list($user_id, $token) = explode(':', $_COOKIE['logincookie']);
			// debug($user_id, $token);
			$lc->load("*", array("token = :token AND user_id = :user_id", ":token" => $token, ":user_id" => $user_id));


			// we didn't have a correct cookie, get out of here
			if ($lc->id) {
				$this->id = $user_id;
				$this->token = $token;
				$this->logged_in = true;
				return true;
			}
		}
		
		// we can also use session
		$user_id = session()->get('user_id');
		$token = session()->get('token');
		$lc->load("*", array("token = :token AND user_id = :user_id", ":token" => $token, ":user_id" => $user_id));

		// we didn't have a correct session, get out of here
		if ($lc->id) {
			$this->id = $user_id;
			$this->token = $token;
			$this->logged_in = true;
			return true;
		}

		return false;
	}

	// PERMISSIONS, YAY
	function can($request = null) {
		if (! $this->logged_in) { return false; }
		if (! $this->role_id) { return false; }
		if (! $this->permissions) {
			$role = new Role();
			$role->load("*", "id = {$this->role_id}");

			$this->permissions = unserialize($role->permissions);
			$this->role = $role->label;
		}

		$permissions = $this->permissions;
		if ($request == null) {return;}
		if ($permissions == null) {return;}

		if (is_array($request)) {
			// return singular permission status
			foreach ($request as $r) {
				if ($this->can($r)) {
					return true;
				}
			}
			return false;
		}

		// administratos can do literally anything
		if (in_array("administrator", $permissions)) { return true; }

		// return singular permission status
		if (in_array($request, $permissions)) { return true; }

		// any of these permissions allows you backend access
		if ($request == "access_admin") {
			if (in_array("manage_settings", $permissions)) { return true; }
			if (in_array("manage_users", $permissions)) { return true; }
			if (in_array("manage_roles", $permissions)) { return true; }
			if (in_array("manage_post_types", $permissions)) { return true; }
			if (in_array("manage_custom_fields", $permissions)) { return true; }
			if (in_array("manage_forms", $permissions)) { return true; }
			if (in_array("manage_menus", $permissions)) { return true; }
			if (in_array("manage_comments", $permissions)) { return true; }
			if (in_array("manage_widgets", $permissions)) { return true; }
		}


		return false;
	}

	function logged_in() {
		// var_dump($this->remembered());
		return $this->remembered();
	}
}

// Login Cookies
class LoginCookie extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"token" => array(
				"type" => "VARCHAR(256)"
			), 
			"user_id" => array(
				"type" => "INT(7)"
			),
			"created" => false
		);

		parent::__construct("rf_login_cookies", $schema);
	}
}

// Login Cookies
class VerifyCode extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"code" => array(
				"type" => "VARCHAR(256)"
			), 
			"user_id" => array(
				"type" => "INT(7)"
			),
			"modified" => false
		);

		parent::__construct("rf_verify_codes", $schema);
	}
}
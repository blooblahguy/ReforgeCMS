<?




	class User extends RF_Model {
		public $logged_in = false;
		public $role;
		private
			$uid = 0,
			$permissions = array();

		function __construct() {
			
			$this->model_table = "users";
			$this->model_schema = array(
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
				"avatar" => array(
					"type" => "VARCHAR(256)"
				),
				"last_login" => array(
					"type" => "DATETIME",
				)
			);

			parent::__construct();
		}

		function get_user($id = 0) {
			if ($id === 0) {
				if ($this->remembered()) {
					$user_id = session()->get("user_id");
					$this->load("id = $user_id");
					$this->logged_in = true;
					$this->check_avatar();
				}
			} else {
				$this->load("id = $id");
				$this->check_avatar();
			}
		}

		function check_avatar() {
			global $root;
			if ($this->avatar == "" || ! file_exists($root.$this->avatar)) {
				$img = new RF_File();
				$avatar = $img->create_id_img($this->username);
				$this->avatar = $avatar;
				$this->update();
			}
		}


		static function logout($core, $args) {
			global $db;

			session()->clear("user_id");
			var_dump(session()->get("user_id"));

			$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : false;

			if (! ! $cookie) {
				list ($user_id, $token) = explode(':', $cookie);
				$db->exec("DELETE FROM login_cookies WHERE token = :token AND user_id = :user_id", array(
					":token" => $token,
					":user_id" => $user_id,
				));
			}
			
			\Alerts::instance()->success("Logged out");
			redirect("/");
		}

		static function login($core, $args) {
			global $db;

			$crypt = \Bcrypt::instance();
			$email = $_POST['email'];
			$password = $_POST['password'];
			$redirect = $_POST["redirect"];

			$user = $db->exec("SELECT id, email, password FROM users WHERE email = :email", array(
				":email" => $email
			));


			if (count($user) > 0) {
				$user = $user[0];
				if ($crypt->verify($password, $user['password'])) {
					// store this login
					$token = random_bytes(32);
					$cookie = $token . time();
					$token = crypt($cookie, $core->get("salt")); 
					$cookie = $user['id'] . ":" . $token; // sets the cookie string
					setcookie('rememberme', $cookie, time() + 60 * 60 * 24 * 30, "/"); // 30 days
					
					$count = $db->exec("UPDATE login_cookies SET token = :token, created = NOW() WHERE user_id = :user_id", array(
						":token" => $token,
						":user_id" => $user['id'],
					));

					if ($count == 0) {
						$db->exec("INSERT INTO login_cookies (token, user_id, created) VALUES (:token, :user_id, NOW())", array(
							":token" => $token,
							":user_id" => $user['id'],
						));
					}
					
					// destroy any cookies that are older than 30 days
					$db->exec("DELETE FROM login_cookies WHERE created < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)) "); 

					// save in session now
					session()->set("user_id", $user["id"]);

					// now log them in
					$db->exec("UPDATE users SET last_login = NOW() WHERE email = :email", array(
						":email" => $email
					));

					\Alerts::instance()->success("Successfully logged in, welcome back {$user['name']}");
					redirect($redirect);
				}
			}

			\Alerts::instance()->error("Invalid username or password");
			redirect($redirect);
		}

		private function remembered() {
			global $core;
			global $db;

			// using session to see if they're logged in
			$session = session()->get('user_id');
			if ($session !== null) { 
				$this->uid = $session;
				return true;
			}

			$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : false;
			list ($user_id, $token) = explode(':', $cookie);

			if (! $cookie) {
				return false;
			}

			// We don't have a session but we do have a cookie, lets reup this session if we can
			$cookies = $db->exec("SELECT * FROM login_cookies WHERE token = :token AND user_id = :user_id", array(
				":token" => $token,
				":user_id" => $user_id,
			));

			foreach ($cookies as $saved) {
				if ($token == $saved["token"]) {
					session()->set('user_id', $saved['user_id']);
					setcookie('rememberme', $user_id.":".$token, time() + 60 * 60 * 24 * 30, "/"); // reup the cookie (30 days)
					$rs = $db->exec("UPDATE login_cookies SET created = NOW() WHERE token = :token AND user_id = :user_id", array(
						":token" => $token,
						":user_id" => $user_id	
					)); // reup the database save date

					$this->uid = $user_id;

					return true;
				}
			}

			return false;
		}

		// PERMISSIONS, YAY
		function can($request = null) {
			if (! count($this->permissions)) {
				$role = new Role();
				$role->load("id = {$this->role_id}");
				
				// // $rs = $db->exec("SELECT permissions FROM roles WHERE id = {$this->role_id}");
				$this->permissions = unserialize($role->permissions);
				$this->role = $role->label;
			}

			return true; 
			$permissions = $this->permissions;
			if ($request == null) {return;}

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
			return $this->logged_in;
		}
	}

	// Check if current user is logged in
	function logged_in() {
		$user = current_user();
		
		return $user->logged_in;
	}

	function get_user($id = 0) {
		// current user
		$current = current_user();
		if ($id == 0 || $id == $current->id) { return current_user(); }

		$user = new User();
		$user->get_user($id);		
	}

	function current_user() {
		if (! Registry::exists("current_user")) {
			$user = new User();
			$user->get_user();
			Registry::set("current_user", $user);
		}
		return Registry::get("current_user");
	}

	// Login Cookies
	\RF_Schema::instance()->add("login_cookies", array(
		"token" => array(
			"type" => "VARCHAR(256)"
		), 
		"user_id" => array(
			"type" => "INT(7)"
		), 
	));
?>
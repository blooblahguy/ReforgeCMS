<?

	class User extends \DB\SQL\Mapper {
		private $logged_in = false, $self = false, $cookie_uid = 0, $token = 0, $permissions = false;

		function __construct($id = 0) {
			global $db;

			parent::__construct( $db, 'users' );


			if (is_int($id) && $id == 0) {
				// attempt to get currently logged in user
				if ($this->remembered()) {

					$this->load("id = ".$this->cookie_uid);
					if ($this->count() == 1) {
						$this->logged_in = true;
						$this->self = true;
					} 
				}
			} elseif (is_int($id)) {
				// query user from database
				$this->load("id = $id");
			}
		}

		static function logout($core, $args) {
			global $db;
			global $alert;
			
			$core->clear("SESSION.user_id");

			$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : false;

			if (! ! $cookie) {
				list ($user_id, $token) = explode(':', $cookie);
				$db->exec("DELETE FROM login_cookies WHERE token = :token AND user_id = :user_id", array(
					":token" => $token,
					":user_id" => $user_id,
				));
			}
			
			$alert->success("Logged out");
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
					$core->set("SESSION.user_id", $user["id"]);

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
			if (isset($core->get['SESSION.user_id'])) { 
				return true;
			}

			$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : false;
			list ($user_id, $token) = explode(':', $cookie);

			if (! $cookie) {
				return false;
			}


			$cookies = $db->exec("SELECT * FROM login_cookies WHERE token = :token AND user_id = :user_id", array(
				":token" => $token,
				":user_id" => $user_id,
			));

		
			foreach ($cookies as $saved) {
				if ($token == $saved["token"]) {
					$core->set['SESSION.user_id'] = $saved['user_id'];
					setcookie('rememberme', $user_id.":".$token, time() + 60 * 60 * 24 * 30, "/"); // reup the cookie (30 days)
					$rs = $db->exec("UPDATE login_cookies SET created = NOW() WHERE token = :token AND user_id = :user_id", array(
						":token" => $token,
						":user_id" => $user_id	
					)); // reup the database save date

					$this->token = $token;
					$this->cookie_uid = $user_id;

					return true;
				}
			}

			return false;
		}

		// PERMISSIONS, YAY
		function can($request) {

			if (! $this->permissions) {
				global $db;
				$rs = $db->exec("SELECT permissions FROM roles WHERE id = {$this->role_id}");
				$this->permissions = unserialize($rs[0]["permissions"]);
			}
			$permissions = $this->permissions;

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

?>
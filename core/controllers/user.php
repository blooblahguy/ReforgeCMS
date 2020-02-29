<?

	class RF_Model extends \DB\SQL\Mapper {
		function __construct() {
			global $db;
			parent::__construct($db, $this->table);
		}

		function load($filter = NULL, ?array $options = NULL, $ttl = 0) {
			parent::load($filter, $options, $ttl);
		}

		function save() {
			parent::save();
		}
	}

	class User extends \DB\SQL\Mapper {
		private $schema = array();
		private $logged_in = false, 
			$self = false, 
			$uid = 0, 
			$token = 0, 
			$db_table = 0, 
			$permissions = false;

		function load($filter = NULL, ?array $options = NULL, $ttl = 0) {
			parent::load($filter, $options, $ttl);
		}
		function get_user($id) {
			$rs = $this->load("id = ".$this->uid);
			if ($rs !== false) {
				return $this;
			}
			return false;
		}

		function get_current_user() {
			if ($this->remembered()) {
				$rs = $this->load("id = ".$this->uid, null, 10 * 10);
				if ($rs !== false) {
					$this->logged_in = true;
					$this->self = true;
				}
			}
		}

		function __construct($ttl = 10000) {
			global $db, $core;
			if ($core->get("schema_updated")) {
				$ttl = 0;
			}

			parent::__construct( $db, 'users' );
		}

		static function logout($core, $args) {
			global $db;
			
			$core->clear("SESSION.user_id");

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
			if ($core->get('SESSION.user_id')) { 
				$this->uid = $core->get('SESSION.user_id');
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
					$core->set('SESSION.user_id', $saved['user_id']);
					setcookie('rememberme', $user_id.":".$token, time() + 60 * 60 * 24 * 30, "/"); // reup the cookie (30 days)
					$rs = $db->exec("UPDATE login_cookies SET created = NOW() WHERE token = :token AND user_id = :user_id", array(
						":token" => $token,
						":user_id" => $user_id	
					)); // reup the database save date

					$this->token = $token;
					$this->uid = $user_id;

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
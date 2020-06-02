<?

class Forms extends \Prefab {
	function __construct() {
		global $core;
		$user = current_user();

		// hook routes if user is logged in
		if ($user->logged_in()) {
			$core->route("POST /profile_submit", "Forms->profile_submit");
			$core->route("GET /logout", "User->logout");
		} else {
			$core->route("POST /register", "Forms->register_submit");
			$core->route("POST /login", "Forms->login_submit", 0, 64);
		}

		$core->route("POST /rf_form/process/@id", function($core, $args) {
			$id = $args['id'];
			$form = new Form();
			$form->load("*", array("id = :id", ":id" => $id));

			$form->submit();
		
			exit();
		});
	}

	function login_submit($core, $args) {
		$user = new User();
		$redirect = $_POST["redirect"];
		$captcha = new \Web\Google\Recaptcha();

		if (! $captcha->verify("6LdLF_8UAAAAAMFZM_8K_7x1KAIVwo1VGvJ7acXO")) {
			Alerts::instance()->error("Invalid recaptcha");
			redirect();
		}

		if ( $user->login() ) {
			// we're in here, redirect
			\Alerts::instance()->success("Successfully logged in, welcome back {$user->name}");
			redirect($redirect);
		}

		\Alerts::instance()->error("Invalid username or password");
		redirect();
	}

	function profile_submit($core, $args) {
		$user = current_user();

		$user->email = $_POST['email'];
		$user->username = $_POST['username'];
		$user->twitch = $_POST['twitch'];

		exit();
	}

	function register_submit($core, $args) {
		$user = new User();
		$redirect = $_POST["redirect"];
		$captcha = new \Web\Google\Recaptcha();

		// verify captcha
		if (! $captcha->verify("6LdLF_8UAAAAAMFZM_8K_7x1KAIVwo1VGvJ7acXO")) {
			Alerts::instance()->error("Invalid recaptcha");
			redirect();
		}

		// verify email
		if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			Alerts::instance()->error("Invalid email address");
			redirect();
		}

		$check = new User();
		$check = $check->find("id", array("email = :email", ":email" => $_POST['email']));
		if (count($check) > 0) {
			Alerts::instance()->error("User with that email address already exists");
			redirect();
		}

		// verify passwords
		if ($_POST['password'] != $_POST['confirm_password']) {
			Alerts::instance()->error("Passwords did not match");
			redirect();
		}

		// check required
		if (! $_POST['username'] || ! $_POST['email'] || ! $_POST['class'] || ! $_POST['password']) {
			Alerts::instance()->error("Please fill out all required fields");
			redirect();
		}
		
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		$default_role = new Role();
		$default_role = $default_role->load("id", "default = 1");

		$user->password = $password;
		$user->username = $_POST['username'];
		$user->email = $_POST['email'];
		$user->twitch = $_POST['twitch'];
		$user->class = $_POST['class'];
		$user->role_id = $default_role->id;
		$user->save();

		Alerts::instance()->success("Account created");
		$user->login();
		redirect($redirect);

		// exit();
	}
}

new Forms();

/**
 * Display a login form to the current user
 */
function login_form($options = array()) {
	if (logged_in()) {
		redirect("/");
		return false;
	}

	?>

	<form method="POST" id="recaptcha-form" action="/login">
		<h2>Login</h2>
		<p>Don't have an account? <a href="/register">Register</a></p>
		<div class="row g1">
			<div class="formsec os-12">
				<label for="">Email</label>
				<input type="text" name="email" required>
			</div>
			<div class="formsec os-12">
				<label for="">Password</label>
				<input type="password" name="password" required>
			</div>
			<input type="hidden" name="redirect" value="/">
			<div class="formsec os-12">
				<? add_recaptcha("Login"); ?>
				<!-- <input type="submit" value="Login"> -->
			</div>
		</div>
	</form>
	<?
}

add_shortcode("login_form", "login_form");

/**
 * Display a registration form for the current user
 */
function registration_form($options) {
	global $wow_classes;
	if (logged_in()) {
		redirect("/");
		return false;
	}
	$user = array();

	?>

	<form method="POST" id="recaptcha-form" action="/register">
		<h2>Register</h2>
		<p>Already have an account? <a href="/login">Login</a></p>
		<div class="row g1">
			<?
			render_html_field($user, array(
				"label" => "Email",
				"type" => "text",
				"name" => "email",
				"required" => true,
				"layout" => "os-12",
			));

			render_html_field($user, array(
				"label" => "Username",
				"type" => "text",
				"name" => "username",
				"required" => true,
				"layout" => "os-12",
			));

			render_html_field($user, array(
				"label" => "Password",
				"type" => "text",
				"name" => "password",
				"layout" => "os-6",
				"required" => true,
			));

			render_html_field($user, array(
				"label" => "Confirm Password",
				"type" => "text",
				"name" => "confirm_password",
				"layout" => "os-6",
				"required" => true,
			));

			render_html_field($user, array(
				"label" => "Twitch Username",
				"type" => "text",
				"name" => "twitch",
				"layout" => "os-6",
			));

			render_html_field($user, array(
				"label" => "Class",
				"type" => "select",
				"choices" => $wow_classes,
				"name" => "class",
				"layout" => "os-6",
				"required" => true,
			));

			?>
		</div>
		<br>

		<? add_recaptcha("Create Account"); ?>
	</form>

	<?

}

add_shortcode("registration_form", "registration_form");

function profile_form($attrs) {
	global $wow_classes;
	if (! logged_in()) {
		redirect("/login");
		return false;
	}

	$user = current_user();

	?>

	<form method="POST" action="/profile">
		<h2>Profile</h2>
		<div class="row g1">
			<div class="os-2 text-center">
				<label for="">Avatar</label>
				<div class="avatar">
					<img src="<?= current_user()->avatar; ?>" alt="">
				</div>
				<input type="file" name="avatar">
			</div>
			<div class="os padt0">
				<div class="row g1">
					<? 
					render_html_field($user, array(
						"label" => "Email",
						"type" => "text",
						"name" => "email",
						"required" => true,
						"layout" => "os-12",
					));

					render_html_field($user, array(
						"label" => "Username",
						"type" => "text",
						"name" => "username",
						"required" => true,
						"layout" => "os-12",
					));

					render_html_field($user, array(
						"label" => "Twitch",
						"type" => "text",
						"name" => "twitch",
						"layout" => "os-6",
					));

					render_html_field($user, array(
						"label" => "Class",
						"type" => "select",
						"choices" => $wow_classes,
						"name" => "class",
						"layout" => "os-6",
					));

					do_action("admin/custom_fields", "user");
					// $fields = RCF()->load_fields("user", $user->id);
					// debug($fields);
					// $cf = new CustomField();
					// $field = reset($cf->find("*", array("id = :id", ":id" => $user->id)));

					// RCF()->render_fields($field['id'], $user->id, "user", $field);
					?>
				</div>
				<input type="submit" value="Save">
			</div>
		</div>
	</form>

	<?
}

add_shortcode("profile_form", "profile_form");

function render_entry_results($entry_id, $args = array()) {
	$entry = new Post();
	$entry->load("*", array("id = :id", ":id" => $entry_id));

	$form = new Form();
	$form = $form->load("*", array("id = :id", ":id" => $entry->post_parent));

	$cf = new CustomField();
	$field = reset($cf->find("*", array("id = :id", ":id" => $form->post_parent)));

	RCF()->render_results($field['id'], $entry_id, "application", $field);
}

function render_entry($entry_id, $args = array()) {
	$entry = new Post();
	$entry->load("*", array("id = :id", ":id" => $entry_id));

	$form = new Form();
	$form = $form->load("*", array("id = :id", ":id" => $entry->post_parent));

	$cf = new CustomField();
	$field = reset($cf->find("*", array("id = :id", ":id" => $form->post_parent)));

	RCF()->render_fields($field['id'], $entry_id, "application", $field);
}

function render_form($uid, $args = array()) {
	$form = new Form();

	if (is_numeric($uid)) {
		$form->load("*", array("id = :id AND post_type = 'forms' ", ":id" => $uid));
	} else {
		$form->load("*", array("slug = :slug AND post_type = 'forms' ", ":slug" => $uid));
	}

	$form->render($args);
}

add_shortcode("form", function($attrs) {
	render_form($attrs['slug']);
});
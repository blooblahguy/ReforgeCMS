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
			$core->route("POST /register_submit", "Forms->register_submit");
			$core->route("POST /login", "Forms->login_submit");
		}
	}

	function login_submit($core, $args) {
		$user = new User();
		$redirect = $_POST["redirect"];

		if ( $user->login() ) {
			// we're in here, redirect
			\Alerts::instance()->success("Successfully logged in, welcome back {$user->name}");
			redirect($redirect);
		}

		\Alerts::instance()->error("Invalid username or password");
		redirect($redirect);
	}

	function profile_submit($core, $args) {

	}

	function register_submit($core, $args) {

	}
}

/**
 * Display a login form to the current user
 */
function login_form($options = array()) {
	$user = current_user();
	// if ($user->logged_in()) {
		// return false;
	// }

	?>
	<form method="POST" action="/login">
		<h2>Login</h2>
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
				<input type="submit" value="Login">
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
	$user = current_user();
	if ($user->logged_in()) {
		return false;
	}


}

add_shortcode("registration_form", "registration_form");
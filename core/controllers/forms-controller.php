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
 * Display a profile form for the current user
 */
function profile_form($options) {
	$user = current_user();
	if (! $user->logged_in()) {
		return false;
	}

	$rank = new Role();
	$rank->load(array("id = :id", ":id" => $user->role_id));

	?>
	<form enctype="multipart/form-data" action="/profile_submit" method="POST">
		<div class="row g1 padb2">
			<div class="os-2 avatar text-center">
				<div class="avatar preview">
					<? $user->render_avatar(); ?>
				</div>
				<div class="rank"><?= $rank->label; ?></div>
			</div>
			<div class="os profilemain">
				<? 
				render_admin_field($user, array(
					"type" => "text",
					"label" => "Username",
					"name" => "username",
				));
				render_admin_field($user, array(
					"type" => "text",
					"label" => "Email",
					"name" => "email",
					"autocomplete" => "email",
				));
				?>
				<? do_action("admin/custom_fields", "user"); ?>
			</div>
		</div>
		<div class="row g1 padb2">
			<div class="os">
				<?
				render_admin_field("", array(
					"type" => "password",
					"name" => "password_reset",
					"label" => "New Password",
				));
				?>
			</div>
			<div class="os">
				<?
				render_admin_field("", array(
					"type" => "password",
					"name" => "password_reset_confirm",
					"label" => "Confirm New Password"
				));
				?>
			</div>
		</div>
		<input type="submit" value="Save">
	</form>
	<?
}

add_shortcode("profile_form", "profile_form");

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
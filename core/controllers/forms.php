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

		$core->route("POST /rf_form/process/@id", function($core, $args) {
			$id = $args['id'];
			$form = new Form();
			$form->load("*", array("id = :id", ":id" => $id));

			$form->submit();
		
			exit();
		});
	}

	// function login_submit($core, $args) {
	// 	$user = new User();
	// 	$redirect = $_POST["redirect"];

	// 	if ( $user->login() ) {
	// 		// we're in here, redirect
	// 		\Alerts::instance()->success("Successfully logged in, welcome back {$user->name}");
	// 		redirect($redirect);
	// 	}

	// 	\Alerts::instance()->error("Invalid username or password");
	// 	redirect($redirect);
	// }

	function profile_submit($core, $args) {

	}

	function register_submit($core, $args) {

	}
}

new Forms();

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
<?php

class Forms extends \Prefab {
	function __construct() {
		global $core;
		$user = current_user();

		// hook routes if user is logged in
		if ( $user->logged_in() ) {
			$core->route( "POST /profile_submit", "Forms->profile_submit" );
			$core->route( "GET /logout", "User->logout" );
			$core->route( "GET /change_password", "Forms->change_password" );
		} else {
			$core->route( "POST /register", "Forms->register_submit" );
			$core->route( "POST /login", "Forms->login_submit", 0, 64 );

			// request and reset password
			$core->route( "POST /reset_password_request", "Forms->reset_password_request" );
			$core->route( "POST /change_password", "Forms->change_password" );
		}

		$core->route( "POST /rf_form/process/@id", function ($core, $args) {
			$id = $args['id'];
			$form = new Form();
			$form->load( "*", array( "id = :id", ":id" => $id ) );

			$form->submit();

			exit();
		} );
	}

	function login_submit( $core, $args ) {
		$user = new User();
		$redirect = $_POST["redirect"];
		// $captcha = new \Web\Google\Recaptcha();

		// if (! $captcha->verify("6LdLF_8UAAAAAMFZM_8K_7x1KAIVwo1VGvJ7acXO")) {
		// 	Alerts::instance()->error("Invalid recaptcha");
		// 	redirect();
		// }

		if ( $user->login() ) {
			// we're in here, redirect
			\Alerts::instance()->success( "Successfully logged in, welcome back {$user->name}" );
			redirect( $redirect );
		}

		\Alerts::instance()->error( "Invalid username, password, or the account is not verified" );
		redirect();
	}

	function register_submit( $core, $args ) {
		$user = new User();
		$redirect = $_POST["redirect"];
		$captcha = new \Web\Google\Recaptcha();

		// verify captcha
		if ( ! $captcha->verify( "6LdLF_8UAAAAAMFZM_8K_7x1KAIVwo1VGvJ7acXO" ) ) {
			Alerts::instance()->error( "Invalid recaptcha" );
			redirect();
		}

		// verify email
		if ( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ) {
			Alerts::instance()->error( "Invalid email address" );
			redirect();
		}

		$check = new User();
		$check = $check->find( "id", array( "email = :email", ":email" => $_POST['email'] ) );
		if ( count( $check ) > 0 ) {
			Alerts::instance()->error( "User with that email address already exists" );
			redirect();
		}

		// verify passwords
		if ( $_POST['password'] != $_POST['confirm_password'] ) {
			Alerts::instance()->error( "Passwords did not match" );
			redirect();
		}

		// check required
		if ( ! $_POST['username'] || ! $_POST['email'] || ! $_POST['class'] || ! $_POST['password'] ) {
			Alerts::instance()->error( "Please fill out all required fields" );
			redirect();
		}

		$password = password_hash( $_POST['password'], PASSWORD_DEFAULT );

		// $default_role = new Role();
		// $default_role = $default_role->load("id", "default = 1");
		$default_role = get_option( "default_role" );

		$user->password = $password;
		$user->username = $_POST['username'];
		$user->email = $_POST['email'];
		$user->twitter = $_POST['twitter'];
		$user->twitch = $_POST['twitch'];
		$user->class = $_POST['class'];
		$user->role_id = $default_role;
		$user->save();

		$code = new VerifyCode();
		$code->code = uniqid();
		$code->user_id = $user->id;
		$code->save();

		rf_mail( $user->email, "Verify Your Email Address", "Click the link below to verify your email address. <br> <a href='https://bigdumb.gg/verify?code=" . $code->code . "'>Verify Email</a>" );

		Alerts::instance()->success( "Account created, please check your email to verify your account" );
		redirect( $redirect );
	}

	function change_password() {
		$code = $_POST['code'];
		$password = $_POST['code'];
		$password_confirm = $_POST['code'];

		$verify = new VerifyCode();
		$verify->load( "*", array( "code = :code", ":code" => $code ) );

		if ( $verify->id ) {
			// make sure our passwords match
			if ( $password != $password_confirm ) {
				Alerts::instance()->error( "Passwords do not match" );
				redirect();
			}

			$password = password_hash( $_POST['password'], PASSWORD_DEFAULT );

			$user = new User();
			$user->load( "*", array( "id = :id", ":id" => $verify->user_id ) );
			$user->password = $password;
			$user->save();

			$verify->erase();

			Alerts::instance()->success( "Password successfuly changed" );
			redirect( "/login" );
		} else {
			Alerts::instance()->error( "Invalid verification code" );
			redirect( "/" );
		}
	}

	function reset_password_request() {
		$email = $_POST['email'];

		$user = new User();
		$user->load( "id, email", array( "email = :email", ":email" => $email ) );

		if ( $user->id ) {
			$code = new VerifyCode();
			$code->code = uniqid();
			$code->code_type = "reset";
			$code->user_id = $user->id;
			$code->save();

			rf_mail( $user->email, "Reset Password Request", "Click the link below to reset your account password. <br> <a href='https://bigdumb.gg/forgot-password?code=" . $code->code . "'>Reset Password</a>" );
		}
		Alerts::instance()->success( "If that email exists, a reset password has been sent to them" );
		redirect( "/" );
	}

	// function verify() {
	// 	$code = $_GET['code'];

	// 	$verify = new VerifyCode();
	// 	$verify->load("*", array("code = :code", ":code" => $code));

	// 	if ($verify->id) {
	// 		$user = new User();
	// 		$user->load("*", array("id = :id", ":id" => $verify->user_id));
	// 		$user->verified = 1;
	// 		$user->save();

	// 		$verify->erase();

	// 		Alerts::instance()->success("Email successfuly verified, you can log in now.");
	// 		redirect("/login");
	// 	} else {
	// 		Alerts::instance()->error("Invalid verification code");
	// 		redirect("/");
	// 	}
	// }


	function profile_submit( $core, $args ) {
		$user = current_user();

		$user->email = $_POST['email'];
		$user->username = $_POST['username'];
		$user->twitch = $_POST['twitch'];
		$user->character_name = $_POST['character_name'];
		$user->class = $_POST['class'];
		$user->twitter = $_POST['twitter'];
		$user->bio = $_POST['bio'];

		// debug($_FILES);
		// exit();

		// $avatar = Media::instance()->upload($core, $args);
		// $user->avatar = $avatar->get_size(200, 200);

		$user->save();

		redirect( "/profile" );

		exit();
	}
}

new Forms();

/**
 * Display a login form to the current user
 */
function login_form( $options = array() ) {
	if ( logged_in() ) {
		redirect( "/" );
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
			<a href="/forgot-password">Forgot Password</a>
			<div class="formsec os-12">
				<? add_recaptcha( "Login" ); ?>
				<!-- <input type="submit" value="Login"> -->
			</div>
		</div>
	</form>
<?
}

add_shortcode( "login_form", "login_form" );

/**
 * Display a registration form for the current user
 */
function registration_form( $options ) {
	global $wow_classes;
	if ( logged_in() ) {
		redirect( "/" );
		return false;
	}
	$user = array();

	?>

	<form method="POST" id="recaptcha-form" action="/register">
		<h2>Register</h2>
		<p>Already have an account? <a href="/login">Login</a></p>
		<div class="row g1">
			<?
			render_html_field( $user, array(
				"label" => "Email",
				"type" => "text",
				"name" => "email",
				"required" => true,
				"layout" => "os-12",
			) );

			render_html_field( $user, array(
				"label" => "Username",
				"type" => "text",
				"name" => "username",
				"required" => true,
				"layout" => "os-12",
			) );

			render_html_field( $user, array(
				"label" => "Password",
				"type" => "password",
				"name" => "password",
				"layout" => "os-6",
				"required" => true,
			) );

			render_html_field( $user, array(
				"label" => "Confirm Password",
				"type" => "password",
				"name" => "confirm_password",
				"layout" => "os-6",
				"required" => true,
			) );

			render_html_field( $user, array(
				"label" => "Twitter Username",
				"type" => "text",
				"name" => "twitter",
				"layout" => "os-12",
			) );

			render_html_field( $user, array(
				"label" => "Twitch Username",
				"type" => "text",
				"name" => "twitch",
				"layout" => "os-12",
			) );

			render_html_field( $user, array(
				"label" => "Character Name",
				"type" => "text",
				"name" => "character_name",
				"layout" => "os-6",
				"required" => true,
			) );
			render_html_field( $user, array(
				"label" => "Class",
				"type" => "select",
				"choices" => $wow_classes,
				"name" => "class",
				"layout" => "os-6",
				"required" => true,
			) );

			?>
		</div>
		<br>

		<? add_recaptcha( "Create Account" ); ?>
	</form>

<?

}

add_shortcode( "registration_form", "registration_form" );

function profile_form( $attrs ) {
	global $wow_classes;
	if ( ! logged_in() ) {
		redirect( "/login" );
		return false;
	}

	$user = current_user();

	// debug($user);

	?>

	<form method="POST" action="/profile_submit" enctype='multipart/form-data'>
		<div class="row g1">
			<div class="os padt0">
				<div class="row g1">
					<?
					render_html_field( $user, array(
						"label" => "Email",
						"type" => "text",
						"name" => "email",
						"required" => true,
						"layout" => "os-12",
					) );

					render_html_field( $user, array(
						"label" => "Username",
						"type" => "text",
						"name" => "username",
						"required" => true,
						"layout" => "os-12",
					) );

					render_html_field( $user, array(
						"label" => "Twitch Username",
						"type" => "text",
						"name" => "twitch",
						"placeholder" => "Twitch Channel Username",
						"layout" => "os-12",
					) );

					render_html_field( $user, array(
						"label" => "Twitter Username",
						"type" => "text",
						"name" => "twitter",
						"layout" => "os-12",
					) );

					render_html_field( $user, array(
						"label" => "Main Character Name",
						"type" => "text",
						"required" => true,
						"name" => "character_name",
						"layout" => "os-6",
					) );

					render_html_field( $user, array(
						"label" => "Class",
						"type" => "select",
						"choices" => $wow_classes,
						"required" => true,
						"name" => "class",
						"layout" => "os-6",
					) );

					render_html_field( $user, array(
						"label" => "Bio",
						"type" => "textarea",
						"required" => false,
						"name" => "bio",
						"layout" => "os-12",
					) );


					// $cf = new CustomField();
					// $field = reset($cf->find("*", array("id = :id", ":id" => $user->id)));
				
					// do_action("admin/custom_fields", "user");
				
					?>
				</div>
				<input type="submit" value="Save">
			</div>
		</div>
	</form>

	<script src="/core/assets/js/simplemde.js"></script>
	<script>
		var simplemde = new SimpleMDE({
			element: document.getElementById("bio"),
			spellChecker: false,
			hideIcons: ["fullscreen", "side-by-side", "preview", "table", "image"],
		});
	</script>

<?
}

add_shortcode( "profile_form", "profile_form" );

function forgot_password() {
	if ( logged_in() ) {
		// redirect("/profile");
	}

	$code = $_GET['code'];

	if ( isset( $code ) ) {
		$verify = new VerifyCode();
		$verify = $verify->load( "*", array( "code = :code", ":code" => $code ) );

		if ( ! $verify->id ) {
			\Alerts::instance()->error( "Invalid reset code." );
			redirect( "/login" );
		}

		?>
		<form method="POST" action="/change_password">
			<h2>Set New Password</h2>
			<?
			render_html_field( array(), array(
				"label" => "Password",
				"type" => "password",
				"name" => "password",
				"required" => true,
				"layout" => "os-12",
			) );
			render_html_field( array(), array(
				"label" => "Confirm Password",
				"type" => "password",
				"name" => "password_confirm",
				"required" => true,
				"layout" => "os-12",
			) );
			?>

			<input type="hidden" name="code" value="<?= $code; ?>">
			<br>
			<input type="submit">
		</form>
	<?
	} else {
		?>
		<form method="POST" action="/reset_password_request">
			<h2>Reset Password</h2>
			<?

			render_html_field( array(), array(
				"label" => "Email",
				"type" => "text",
				"name" => "email",
				"required" => true,
				"layout" => "os-12",
			) );

			?>
			<br>
			<input type="submit">
		</form>
	<?
	}
}

add_shortcode( "forgot_password", "forgot_password" );

function render_entry_results( $entry_id, $args = array() ) {
	$entry = new Post();
	$entry->load( "*", array( "id = :id", ":id" => $entry_id ) );

	$form = new Form();
	$form = $form->load( "*", array( "id = :id", ":id" => $entry->post_parent ) );

	$cf = new CustomField();
	$field = reset( $cf->find( "*", array( "id = :id", ":id" => $form->post_parent ) ) );

	// debug($entry);
	// debug($form);
	// debug($field);

	RCF()->render_results( $field['id'], $entry_id, "application", $field );
}

function render_entry( $entry_id, $args = array() ) {
	$entry = new Post();
	$entry->load( "*", array( "id = :id", ":id" => $entry_id ) );

	$form = new Form();
	$form = $form->load( "*", array( "id = :id", ":id" => $entry->post_parent ) );

	$cf = new CustomField();
	$field = reset( $cf->find( "*", array( "id = :id", ":id" => $form->post_parent ) ) );

	RCF()->render_fields( $field['id'], $entry_id, "application", $field );
}

function render_form( $uid, $args = array() ) {
	$form = new Form();

	if ( is_numeric( $uid ) ) {
		$form->load( "*", array( "id = :id AND post_type = 'forms' ", ":id" => $uid ) );
	} else {
		$form->load( "*", array( "slug = :slug AND post_type = 'forms' ", ":slug" => $uid ) );
	}

	$form->render( $args );
}

add_shortcode( "form", function ($attrs) {
	render_form( $attrs['slug'] );
} );
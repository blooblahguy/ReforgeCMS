<?php 
// global $core; 
// debug($core);

class RFP_Front extends \Prefab {
	function __construct() {
		global $core; 

		add_shortcode("profile_form", array($this, "profile"));
		$core->route("POST /profile_submit", "RFP_Front->profile_submit");
	}


	function profile($attrs) {
		$user = current_user();
		if (! $user->logged_in()) {
			return false;
		}

		$rank = new Role();
		$rank->load("*", array("id = :id", ":id" => $user->role_id));

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

	function profile_submit($core, $args) {
		$user = current_user();
		if (! $user->logged_in()) {
			redirect($_SERVER['HTTP_REFERER']);
		}

		$user->username = $_POST['username'];
		$user->email = $_POST['email'];
		$user->save();

		\Alerts::instance()->success("Profile updated");

		redirect($_SERVER['HTTP_REFERER']);
	}
}
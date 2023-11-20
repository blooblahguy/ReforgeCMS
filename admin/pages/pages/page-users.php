<?php

class admin_page_USERS extends RF_Admin_Page {
	function __construct() {
		global $core;

		$this->name = "users";
		$this->label = "User";
		$this->label_plural = "Users";
		$this->admin_menu = 30;
		$this->icon = "account_circle";
		$this->permissions = array(
			"all" => "manage_users"
		);
		$this->link = "/admin/{$this->name}";

		// Be sure to set up the parent
		parent::__construct();

		$core->route("GET /admin/users/reset_verify/@id", function($core, $args) {
			$this->resend_verify($args);
		});
	}

	function index($args) {
		$user = new User();
		$role = new Role();

		$this->render_title();

		// query all users, sorted by role
		$all_users = $user->query("SELECT users.*, roles.label as role FROM {$user->table} as users
			LEFT JOIN {$role->table} as roles ON roles.id = users.role_id
			ORDER BY roles.priority ASC, users.role_id ASC, users.id DESC
		");
		$users = array();
		$tabs = array();

		// sort into roles and tabs
		foreach ($all_users as $user) {
			$tabs[$user['role']] = $user['role'];
			$users[$user['role']][] = $user;
		}

		//display columns
		$columns = array(
			'username' => array(
				"label" => "Username",
				"class" => "tablelabel",
				"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
				"calculate" => function($label, $user) {
					global $wow_class_colors;

					// debug($user);

					// return $label;
					return '<a style="color: '.$wow_class_colors[$user['class']].'" href="/admin/users/edit/'.$user['id'].'">'.$user['username'].'</a>';
				}
			),
			'email' => array(
				"label" => "Email",
				"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
			),
			'role' => array(
				"label" => "Role",
			),
			'twitch' => array(
				"label" => "Twitch",
			),
			'twitter' => array(
				"label" => "Twitter",
			),
			'last_login' => array(
				"label" => "Last Login",
				"calculate" => function($label, $r) {
					return Date("Y-m-d", strtotime($label));
				}
			),
			'created' => array(
				"label" => "Member Since",
				"calculate" => function($label, $r) {
					return Date("Y-m", strtotime($label));
				}
			),
			'remove' => array (
				"label" => "Remove",
				"class" => "min",
				"calculate" => function($s, $r) {
					return "<a href='{$this->link}/delete/{$r['id']}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		);
		if (current_user()->can("manage_users")) {
			$columns['mimic'] = array(
				"label" => "Mimic",
				"html" => '<a href="/admin/mimic-user/%2$d">Mimic User</a>',
			);
		}

		?>
		<div class="section tabs">
			<div class="tab_nav">
				<?
				foreach ($tabs as $tab) {
					?>
					<a href="#0" data-tab="<?= slugify($tab); ?>"><?= $tab; ?></a>
					<?
				}
				?>
			</div>
			<? foreach ($tabs as $tab) { 
				$group = $users[$tab]; ?>
				<div class="tab_content" data-tab="<?= slugify($tab); ?>">
					<? display_results_table($group, $columns); ?>
				</div>
			<? } ?>
		</div>
		<?

		// debug($users);
		

		// echo '<div class="section">';
		// // display table
		
		
		// echo '</div>';
	}


	function resend_verify($args) {
		$id = $args['id'];

		$user = new User();
		$user->load("*", array("id = :id", ":id" => $id));

		$code = new VerifyCode();
		$code->code = uniqid();
		$code->user_id = $id; 
		$code->save();

		rf_mail($user->email, "Verify Your Email Address", "Click the link below to verify your email address. <br> <a href='https://bigdumb.gg/verify?code=".$code->code."'>Verify Email</a>");
		// rf_mail("test-u7x0kq1sk@srv1.mail-tester.com", "Verify Your Email Address", "Click the link below to verify your email address. <br> <a href='https://bigdumb.gg/verify?code=".$code->code."'>Verify Email</a>");

		\Alerts::instance()->success("Email resent");
		redirect("/admin/users/edit/{$id}");
	}

	function edit($args) {
		$id = $this->id;

		$user = new User();
		$user->load("*", array("id = :id", ":id" => $id));
		$action = "Create";
		$subject = "User";
		if ($id > 0) {
			$action = "Edit";
			$subject = ucfirst($user->username);
		}

		$user_role = new Role();
		$user_role->load("priority", array("id = :id", ":id" => current_user()->role_id));


		$roles = new Role();
		$roles = $roles->find("*", "priority >= {$user_role->priority}", array("order by" => "priority ASC"));
		$roles = array_extract($roles, "id", "label");



		$classes = array(	
			"deathknight" => "Death Knight",
			"demonhunter" => "Demon Hunter",
			"druid" => "Druid",
			"hunter" => "Hunter",
			"mage" => "Mage",
			"monk" => "Monk",
			"paladin" => "Paladin",
			"priest" => "Priest",
			"rogue" => "Rogue",
			"shaman" => "Shaman",
			"warlock" => "Warlock",
			"warrior" => "Warrior",
		);

		
	?>
		<div class="row g1">
			<div class="os-2">
				<div class="section text-center">
					<a class="btn" href="/admin/users/reset_verify/<?= $user->id; ?>">Resend Verification Email</a>

					<?
					render_html_field($user, array(
						"type" => "checkbox",
						"name" => "verified",
						"label" => "Verified Email",
						)); 
					?>
					
				</div>

				<!-- <div class="section text-center">
					<h4>Avatar</h4>
					<div class="avatar preview">
						<? $user->render_avatar(); ?>
					</div>
					<br>
					<?= Media::instance()->select_button("avatar");?>
					<input type="hidden" name="avatar" value="<?= $user->avatar; ?>">
					<input type="hidden" name="avatar_path" value="<?= $user->avatar; ?>">
					<? if (current_user()->can("administrator")) { ?>
						<a href="/admin/users/reset_avatar/<?= $user->id; ?>">Reset Avatar</a>
					<? } ?>

					
				</div> -->
			</div>
			<div class="os">
				<div class="section">
					<? 

					render_html_field($user, array(
						"type" => "text",
						"name" => "username",
						"label" => "Username",
						"required" => true
					)); 

					render_html_field($user, array(
						"type" => "text",
						"name" => "email",
						"label" => "Email",
						"required" => true
					)); 

					$choices = array("dark" => "Dark", "default" => "Default");
					render_html_field($user, array(
						"type" => "select",
						"label" => "Admin theme",
						"name" => "admin_theme",
						"default" => "default",
						"choices" => $choices,
					));

					render_html_field($user, array(
						"type" => "select",
						"name" => "role_id",
						"label" => "Role",
						"required" => true,
						"choices" => $roles
					)); 

					render_html_field($user, array(
						"type" => "text",
						"name" => "character_name",
						"label" => "Character Name",
					)); 

					render_html_field($user, array(
						"type" => "select",
						"name" => "class",
						"label" => "Class",
						"choices" => $classes
					)); 

					render_html_field($user, array(
						"type" => "text",
						"name" => "twitch",
						"label" => "Twitch Username",
					)); 
					render_html_field($user, array(
						"type" => "text",
						"name" => "twitter",
						"label" => "Twitter Handle",
					)); 
					?>
				</div>
				<? do_action("admin/custom_fields", "user"); ?>
			</div>

			<input type="submit" class="marg0" value="Save">
		</div>
		
		<?
	}

	function save($args) {
		$id = $this->id;

		$user = new User();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$user->load("*", array("id = :id", ":id" => $id));
		}

		// $avatar = get_file_size($_POST["avatar"], 200);
		$user->username = $_POST['username'];
		$user->twitch = $_POST['twitch'];
		$user->twitter = $_POST['twitter'];
		$user->email = $_POST['email'];
		$role_id = $_POST['role_id'];
		if ($role_id >= current_user()->role_id || current_user()->can("administrator")) {
			$user->role_id = $_POST['role_id'];
		}
		$user->admin_theme = $_POST['admin_theme'];
		$user->character_name = $_POST['character_name'];
		$user->class = $_POST['class'];
		$user->verified = $_POST['verified'];
		$user->save();

		RCF()->save_fields("user", $user->id);

		\Alerts::instance()->success("User $user->username $changed");
		redirect("/admin/users/edit/{$user->id}");
	}

	function delete($args) {
		$user = new User();
		$user->id = $args['id'];

		$user->erase();

		\Alerts::instance()->success("User $user->username deleted");
		redirect("/admin/users");
	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

new admin_page_USERS();


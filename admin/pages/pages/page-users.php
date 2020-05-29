<?

class admin_page_USERS extends RF_Admin_Page {
	function __construct() {
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
	}

	function index($args) {
		$user = new User();
		$role = new Role();

		$this->render_title();

		$users = $user->query("SELECT users.*, roles.label AS role FROM {$user->table} as users
			LEFT JOIN {$role->table} as roles ON roles.id = users.role_id
			ORDER BY roles.priority ASC, users.role_id ASC, users.id ASC
		");

		echo '<div class="section">';
		// display table
		$columns = array(
			'username' => array(
				"label" => "Username",
				"class" => "tablelabel",
				"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
			),
			'email' => array(
				"label" => "Email",
				"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
			),
			'role' => array(
				"label" => "Role",
			),
			'last_login' => array(
				"label" => "Last Login",
				"calculate" => function($label, $id) {
					return Date("Y-m-d", strtotime($label));
				}
			),
			'created' => array(
				"label" => "Member Since",
				"calculate" => function($label, $id) {
					return Date("Y-m", strtotime($label));
				}
			),
			'remove' => array (
				"label" => "Remove",
				"class" => "min",
				"calculate" => function($s, $id) {
					return "<a href='{$this->link}/delete/{$id}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		);
		if (current_user()->can("administrator")) {
			$columns['mimic'] = array(
				"label" => "Mimic",
				"html" => '<a href="/admin/mimic-user/%2$d">Mimic User</a>',
			);
		}
		display_results_table($users, $columns);
		echo '</div>';
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

		$roles = new Role();
		$roles = $roles->find("*", null, array("order by" => "priority ASC"));
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
				</div>
			</div>
			<div class="os">
				<div class="section">
					<? 

					render_admin_field($user, array(
						"type" => "text",
						"name" => "username",
						"label" => "Username",
						"required" => true
					)); 

					render_admin_field($user, array(
						"type" => "text",
						"name" => "email",
						"label" => "Email",
						"required" => true
					)); 

					$choices = array("dark" => "Dark", "default" => "Default");
					render_admin_field($user, array(
						"type" => "select",
						"label" => "Admin theme",
						"name" => "admin_theme",
						"default" => "default",
						"choices" => $choices,
					));

					render_admin_field($user, array(
						"type" => "select",
						"name" => "role_id",
						"label" => "Role",
						"required" => true,
						"choices" => $roles
					)); 

					render_admin_field($user, array(
						"type" => "select",
						"name" => "class",
						"label" => "Class",
						"required" => true,
						"choices" => $classes
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
		// $user->avatar = $avatar;
		$user->email = $_POST['email'];
		$user->role_id = $_POST['role_id'];
		$user->admin_theme = $_POST['admin_theme'];
		$user->class = $_POST['class'];
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


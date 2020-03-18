<?

class admin_page_USERS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Settings";
		$this->name = "users";
		$this->label = "User";
		$this->label_plural = "Users";
		$this->admin_menu = 30;
		$this->icon = "account_circle";
		$this->base_permission = "manage_users";
		$this->link = "/admin/{$this->name}";

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		global $db;

		$users = $db->exec("SELECT users.*, roles.label AS role FROM users
			LEFT JOIN roles ON roles.id = users.role_id
			ORDER BY roles.priority ASC, users.role_id ASC, users.id ASC
		"); 

		echo '<div class="section">';
		// display table
		display_results_table($users, array(
			'username' => array(
				"label" => "Username",
				"html" => '<a href="/admin/users/edit/%2$d" class="pad1 display-block strong">%1$s</a>',
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
		));
		echo '</div>';
	}

	function render_edit() {
		global $db;
		$id = $this->id;

		$user = new User();
		$user->load("id = $id");
		$action = "Create";
		$subject = "User";
		if ($id > 0) {
			$action = "Edit";
			$subject = ucfirst($user->username);
		}

		$roles = $db->exec("SELECT * FROM roles ORDER BY `priority` ASC");
		$roles = array_extract($roles, "id", "label");
	?>
		<div class="row g1">
			<div class="os-2">
				<div class="section text-center">
					<h4>Avatar</h4>
					<div class="avatar preview">
						<? $user->render_avatar(); ?>
					</div>
					<br>
					<?= RF_Media::instance()->select_button("avatar");?>
					<input type="hidden" name="avatar" value="<?= $user->avatar; ?>">
					<input type="hidden" name="avatar_path" value="<?= $user->avatar; ?>">
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

					render_admin_field($user, array(
						"type" => "select",
						"name" => "role_id",
						"label" => "Role",
						"required" => true,
						"choices" => $roles
					)); 
					?>
				</div>
				<? do_action("admin/custom_fields", "user"); ?>
			</div>

			<input type="submit" class="marg0" value="Save">
		</div>
		
		<?
	}

	function save_page($core, $args) {
		$id = $this->id;

		$user = new User();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$user->load("id = $id");
		}

		$avatar = $_POST["avatar_path"];

		RCF()->save_fields("user", $id);

		debug($user->avatar);
		debug($user->email);
		debug($user->role_id);
		debug($user->username);

		$user->username = $_POST['username'];
		$user->avatar = $avatar;
		$user->email = $_POST['email'];
		$user->role_id = $_POST['role_id'];

		

		// exit();

		$user->save();

		\Alerts::instance()->success("User $user->username $changed");
		redirect("/admin/users/edit/{$user->id}");
	}

	function delete_page($core, $args) {

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

?>
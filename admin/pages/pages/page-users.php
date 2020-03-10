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

		// display table
		display_results_table($users, array(
			'username' => array(
				"label" => "Username",
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
		));
	}

	function render_edit() {
		global $db;
		$id = $this->id;

		$user = new User();
		$action = "Create";
		$subject = "User";
		if ($id > 0) {
			$user->load("id = $id");
			$action = "Edit";
			$subject = ucfirst($user->username);
		}

		$roles = $db->exec("SELECT * FROM roles ORDER BY `priority` ASC");
	?>
		<div class="row">

			<div class="os">
				<div class="content pad2 padl0">
					<div class="padb2">
						<label for="">Username <span>*</span></label>
						<input type="text" name="username" value="<?= $user->username?>" required placeholder="Username">
					</div>
					<div class="padb2">
						<label for="">Email <span>*</span></label>
						<input type="text" name="email" value="<?= $user->email?>" required placeholder="Email">
					</div>
					<div class="padb2">
						<label for="">Role <span>*</span></label>
						<select name="role_id" required>
							<? foreach ($roles as $role) { ?>
								<option value="<?= $role["id"]; ?>" <? if ($user->role_id == $role["id"]) {echo "selected"; }?>><?= $role["label"]; ?></option>
							<? } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="os-2 sidebar pad3">
				<input type="submit" class="marg0" value="Save">
				<hr>
			</div>
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

		$user->username = $_POST['username'];
		$user->email = $_POST['email'];
		$user->role_id = $_POST['role_id'];

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
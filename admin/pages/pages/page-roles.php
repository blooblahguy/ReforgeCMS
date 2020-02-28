<?

class admin_page_ROLES extends admin_page {
	function __construct() {
		$this->name = "roles";
		$this->label = "Role";
		$this->label_plural = "Roles";
		$this->admin_menu = 35;
		$this->icon = "how_to_reg";
		$this->base_permission = "manage_roles";
		$this->link = "/admin/{$this->name}";

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index() {
		global $db;
		$roles = $db->exec("SELECT roles.* FROM roles ORDER BY `priority` ASC");

		display_results_table($roles, array(
			'label' => array(
				"label" => "Label",
				"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
			),
			'slug' => array(
				"label" => "Slug",
				"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
			),
			'users' => array(
				"label" => "Users",
				"calculate" => function($s, $id) {
					global $db;
					$rs = $db->exec("SELECT id FROM `users` WHERE role_id = $id");
					return (count($rs));
				},
			),
			'default' => array(
				"label" => "Default",
				"class" => "min",
				"calculate" => function($s) {
					if ($s == 1) {
						return "Yes";
					} else {
						return "No";
					}
				}
			),
			'actions' => array (
				"label" => "Actions",
				"class" => "min",
				"calculate" => function($s, $id) {
					return '<a href="/admin/roles/delete/'.$id.'" class="delete">Delete</a>';
				}
			)
		));
	}

	protected function render_edit() {
		global $db;

		$id = $this->id;
		$role = new Role();
		$action = "Create";
		$subject = "Role";
		if ($id > 0) {
			$role->load("id = $id");
			$action = "Edit";
			$subject = ucfirst($role->label);
		}
		if (! $role->color) {$role->color = "#b83336"; }
		if (! $role->priority) {$role->priority = 1; }

		$permissions = unserialize($role->permissions);
		if (! is_array($permissions)) {
			debug("create");
			$permissions = array();
		}

		$defaults = array();
		$defaults[] = array(
			"slug" => "administrator",
			"label" => "Administrator",
			"description" => "Roles with this permissions are granted all permissions, and supercede any other permission rules.",
		);

		$defaults[] = array(
			"slug" => "manage_settings",
			"label" => "Manage Settings",
			"description" => "Users can edit website settings in the admin area.",
		);

		$defaults[] = array(
			"slug" => "manage_users",
			"label" => "Manage Users",
			"description" => "Role can promote or demote users to ranks under their own, as well as create or delete users.",
		);

		$defaults[] = array(
			"slug" => "manage_roles",
			"label" => "Manage Roles",
			"description" => "Role can create addition roles beneath their own and add or remove permissions.",
		);

		$defaults[] = array(
			"slug" => "manage_post_types",
			"label" => "Manage Post Types",
			"description" => "Role can add, delete, or update Custom Post Types. Including their defaults or statuses.",
		);

		$defaults[] = array(
			"slug" => "manage_custom_fields",
			"label" => "Manage Custom Fields",
			"description" => "Role can create, delete, or update custom field layouts.",
		);

		$defaults[] = array(
			"slug" => "manage_forms",
			"label" => "Manage Forms",
			"description" => "Role can create, delete, or update forms. They can also view and manage form entries.",
		);

		$defaults[] = array(
			"slug" => "manage_menus",
			"label" => "Manage Menus",
			"description" => "Role can create, delete, or update menus.",
		);

		$defaults[] = array(
			"slug" => "manage_comments",
			"label" => "Manage Comments",
			"description" => "Role can create, delete, or update comments submitted by users with a lesser role.",
		);

		$defaults[] = array(
			"slug" => "manage_widgets",
			"label" => "Manage Widgets",
			"description" => "Role can create, delete, or update widget and their logic and caching.",
		);

		$defaults[] = array(
			"slug" => "upload_files",
			"label" => "Upload Files",
			"description" => "Role can upload files to the website, front end or backend.",
		);

		$defaults[] = array(
			"slug" => "sync_battlenet",
			"label" => "Sync Battlnet",
			"description" => "Role can synd their BattleNet account to the website for character syncing.",
		);
	?>

	<div class="row">
		<div class="os">

			<div class="content pad2 padl0">
				<div class="padb2">
					<label for="">Slug <span>*</span></label>
					<input type="text" name="slug" value="<?= $role->slug?>" required placeholder="Slug">
				</div>
				<div class="padb2">
					<label for="">Label <span>*</span></label>
					<input type="text" name="label" value="<?= $role->label; ?>" required placeholder="Label">
				</div>

				<h3>General Permissions</h3>
				<div class="row g2">
					<? foreach ($defaults as $perm) { ?>
						<div class="os-lg-3 os-md-4 os-6">
							<div class="row border pad2 h100 role_wrapper <?= $perm["slug"]; ?>">
								<div class="os strong">
									<?= $perm["label"]; ?>
								</div>
								<div class="os-min">
									<input type="checkbox" class="toggle" name="permissions[]" value="<?= $perm["slug"]; ?>" <? if (in_array($perm['slug'], $permissions)) {echo "checked"; } ?>>
								</div>
								<div class="description padt1 os-12"><?= $perm["description"]; ?></div>
							</div>
						</div>
					<? } ?>
				</div>

				<h3>Post Type Permissions</h3>
				<? $cpts = $db->exec("SELECT * FROM post_types ORDER BY `order`"); 
				$rights = array("Create", "Update Any", "Delete Any", "Update Own", "Delete Own", "View");
				?>
				<? foreach ($cpts as $type) { ?>
					<div class="row g1">
						<? foreach ($rights as $right) { ?>
							<div class="os-4 os-md-3 os-lg-2">
								<div class="row border pad1 role_wrapper <?= $type["slug"]; ?>">
									<div class="os strong">
										<?= ucfirst($right)." ".$type["label_plural"]; ?>
									</div>
									<div class="os-min">
										<input type="checkbox" class="toggle" name="permissions[]" value="<?= slugify($right."_".$type["slug"]); ?>" <? if (in_array(slugify($right."_".$type["slug"]), $permissions)) {echo "checked"; } ?>>
									</div>
								</div>
							</div>
						<? } ?>
					</div>
				<? } ?>
			</div>
		</div>

		<div class="os-2 sidebar pad3">
			<input type="submit" class="marg0" value="Save">
			<hr>
			<div class="padb2">
				<label for="">Priority Order</label>
				<input type="number" name="priority" value="<?= $role->priority; ?>">
			</div>
			<div class="padb2">
				<input type="checkbox" name="use_color" class="toggle" value="1" <? if ($role->use_color) {echo "checked"; }?>> Use Role Color
			</div>
			<div class="padb2">
				<input type="color" name="color" value="<?= $role->color; ?>">
			</div>
			<div class="padb2">
				<input type="checkbox" value="1" name="default" <? if ($role->default) { echo "checked"; }?>> Default Role
			</div>
		</div>
	</div>

	<?
	}

	protected function save_page($core, $args) {
		global $db; 
		$id = $this->id;
		$permissions = serialize($_POST['permissions']);

		$role = new Role();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$role->load("id = $id");
		}

		$default = isset($_POST['default']) ? 1 : 0;
		if ($default) {
			$db->exec("UPDATE roles SET `default` = 0 WHERE `default` = 1");
		}

		$role->slug = $_POST["slug"];
		$role->label = $_POST["label"];
		$role->priority = $_POST["priority"];
		$role->use_color = isset($_POST["use_color"]) ? 1 : 0;
		$role->color = $_POST["color"];
		$role->permissions = $permissions;
		$role->default = $default;

		$role->save();

		\Alerts::instance()->success("Role $role->slug $changed");
		redirect("/admin/roles/edit/{$role->id}");
	}

	protected function delete_page($core, $args) {
		$id = $args['id'];
		$role = new Role();
		$role->load("id = $id");
		$role->erase();

		\Alerts::instance()->success("Role deleted");
		redirect("/admin/roles");
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

new admin_page_ROLES();

?>
<?
	$id = $core->get("post_id");
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

	$permissions = array();
	$permissions[] = array(
		"slug" => "administrator",
		"label" => "Administrator",
		"description" => "Roles with this permissions are granted all permissions, and supercede any other permission rules",
	);

	$permissions[] = array(
		"slug" => "manage_settings",
		"label" => "Manage Settings",
		"description" => "Users can edit website settings in the admin area.",
	);

	$permissions[] = array(
		"slug" => "manage_users",
		"label" => "Manage Users",
		"description" => "Role can promote or demote users to ranks under their own, as well as create or delete users.",
	);

	$permissions[] = array(
		"slug" => "manage_roles",
		"label" => "Manage Roles",
		"description" => "Role can create addition roles beneath their own and add or remove permissions",
	);

	$permissions[] = array(
		"slug" => "manage_post_types",
		"label" => "Manage Post Types",
		"description" => "Role can add, delete, or update Custom Post Types. Including their defaults or statuses",
	);

	$permissions[] = array(
		"slug" => "manage_custom_fields",
		"label" => "Manage Custom Fields",
		"description" => "Role can create, delete, or update custom field layouts.",
	);

	$permissions[] = array(
		"slug" => "manage_forms",
		"label" => "Manage Forms",
		"description" => "Role can create, delete, or update forms. They can also view and manage form entries.",
	);

	$permissions[] = array(
		"slug" => "manage_menus",
		"label" => "Manage Menus",
		"description" => "Role can create, delete, or update menus.",
	);

	$permissions[] = array(
		"slug" => "manage_comments",
		"label" => "Manage Comments",
		"description" => "Role can create, delete, or update comments submitted by users with a lesser role.",
	);

	$permissions[] = array(
		"slug" => "manage_widgets",
		"label" => "Manage Widgets",
		"description" => "Role can create, delete, or update widget and their logic and caching.",
	);

	$permissions[] = array(
		"slug" => "upload_files",
		"label" => "Upload Files",
		"description" => "Role can upload files to the website, front end or backend.",
	);

	$permissions[] = array(
		"slug" => "sync_battlenet",
		"label" => "Sync Battlnet",
		"description" => "Role can synd their BattleNet account to the website for character syncing.",
	);

?>

<form action="/admin/roles/save/<?= $id; ?>" method="POST" class="row">
	<div class="os">
		<div class="row content-middle">
			<div class="os-min padr2">
				<h2 class="marg0"><? echo sprintf($core->get("page_title"), $action, $subject); ?></h2>
			</div>
			<div class="os padl2">
				<a href="/admin/roles/edit/0" class="btn">New Role</a>
			</div>
		</div>

		<div class="content pad2 padl0">
			<div class="padb2">
				<label for="">Slug <span>*</span></label>
				<input type="text" name="slug" value="<?= $role->slug?>" required placeholder="Slug">
			</div>
			<div class="padb2">
				<label for="">Label <span>*</span></label>
				<input type="text" name="Label" value="<?= $role->label?>" required placeholder="Label">
			</div>

			<h3>General Permissions</h3>
			<div class="row g2">
				<? foreach ($permissions as $perm) { ?>
					<div class="os-lg-3 os-md-4 os-6 role_wrapper pad2 padb0">
						<div class="row border pad2 h100">
							<div class="os strong">
								<?= $perm["label"]; ?>
							</div>
							<div class="os-min">
								<input type="checkbox" class="toggle" name="role[]" value="<?= $perm["slug"]; ?>">
							</div>
							<div class="description padt1 os-12"><?= $perm["description"]; ?></div>
						</div>
					</div>
				<? } ?>
			</div>

			<h3>Post Type Permissions</h3>
			<? $cpts = $db->exec("SELECT * FROM post_types ORDER BY `order`"); 
			$rights = array("Manage", "Delete", "View");
			?>
			<? foreach ($cpts as $type) { ?>
				<div class="row g2">
					<? foreach ($rights as $right) { ?>
						<div class="os-4 os-md-3 os-lg-2 role_wrapper pad2 padb0">
							<div class="row border pad1">
								<div class="os strong">
									<?= $right." ".$type["label_plural"]; ?>
								</div>
								<div class="os-min">
									<input type="checkbox" class="toggle" name="cpt_role[]" value="<?= $perm["slug"]; ?>">
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
		<label for="">Priority Order</label>
		<input type="number" name="priority" value="<?= $role->priority; ?>">
		<label for="">Role Color</label>
		<div class="row">
			<div class="os-min">
				<input type="checkbox" name="use_color" class="toggle" value="1" <? if ($role->use_color) {echo "checked"; }?>>
			</div>
			<div class="os">
				<input type="color" name="color" value="<?= $role->color; ?>">
			</div>
		</div>
	</div>
</form>
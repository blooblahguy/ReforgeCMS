<?php

class admin_page_ROLES extends RF_Admin_Page {
	function __construct() {
		$this->name = "roles";
		$this->label = "Role";
		$this->label_plural = "Roles";
		$this->admin_menu_parent = 'users';
		$this->admin_menu = 35;
		$this->icon = "how_to_reg";
		$this->permissions = array(
			"all" => "manage_roles"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index( $args ) {
		$user = current_user();


		$this->render_title();
		$roles = new Role();
		$roles = $roles->find( "*", null, array(
			"order by" => "priority ASC"
		) );

		echo '<div class="section">';
		display_results_table( $roles, array(
			'label' => array(
				"label" => "Label",
				"class" => "tablelabel",
				"calculate" => function ($label, $role) {
					return '<a style="color: ' . $role['color'] . '" href="/admin/roles/edit/' . $role['id'] . '">' . $role['label'] . '</a>';
				}
			),
			'slug' => array(
				"label" => "Slug",
				"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
			),
			'users' => array(
				"label" => "Users",
				"calculate" => function ($s, $r) {
					// global $db;
					$rs = new User();
					$rs = $rs->find( "*", "role_id = {$r['id']}" );
					return ( count( $rs ) );
				},
			),
			'default' => array(
				"label" => "Default",
				"class" => "min",
				"calculate" => function ($s) {
					if ( $s == 1 ) {
						return "Yes";
					} else {
						return "No";
					}
				}
			),
			'remove' => array(
				"label" => "Remove",
				"class" => "min",
				"calculate" => function ($s, $r) {
					// debug($r);
					return "<a href='{$this->link}/delete/{$r['id']}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		) );
		echo '</div>';
	}

	function edit( $args ) {
		$this->render_title();

		$id = $this->id;
		$role = new Role();
		if ( $id > 0 ) {
			$role->load( "*", array( "id = :id", ":id" => $id ) );
		}

		if ( ! $role->color ) {
			$role->color = "#b83336";
		}
		if ( ! $role->priority ) {
			$role->priority = 1;
		}

		$permissions = array();
		if ( $role->permissions != "" ) {
			$permissions = unserialize( $role->permissions );
			if ( ! is_array( $permissions ) ) {
				$permissions = array();
			}
		}


		$defaults = get_permissions();
		?>

		<div class="row">
			<div class="os">
				<div class="section">
					<?
					render_html_field( $role, array(
						"type" => "text",
						"label" => "Label",
						"name" => "label",
						"required" => true,
					) );
					render_html_field( $role, array(
						"type" => "text",
						"label" => "Slug",
						"name" => "slug",
						"class" => "padt1",
						"required" => false,
					) );
					?>
				</div>

				<div class="row g1 margb2">
					<? foreach ( $defaults as $perm ) { ?>
						<div class="os-lg-3 os-md-4 os-6">
							<div class="row pad2 section h100 role_wrapper <?= $perm["slug"]; ?>">
								<div class="os strong">
									<?= $perm["label"]; ?>
								</div>
								<div class="os-min">
									<input type="checkbox" class="toggle" name="permissions[]" value="<?= $perm["slug"]; ?>" <? if ( in_array( $perm['slug'], $permissions ) ) {
										  echo "checked";
									  } ?>>
								</div>
								<div class="description padt1 os-12"><?= $perm["description"]; ?></div>
							</div>
						</div>
					<? } ?>
				</div>

				<div class="section">
					<h3>Post Type Permissions</h3>
					<?
					$cpts = get_post_types();
					$rights = array( "Create", "Update Any", "Delete Any", "Update Own", "Delete Own" );
					?>
					<? foreach ( $cpts as $type ) { ?>
						<div class="row g1">
							<? foreach ( $rights as $right ) { ?>
								<div class="os-4 os-md-3 os-lg">
									<div class="row section pad1 role_wrapper <?= $type["slug"]; ?>">
										<div class="os strong">
											<?= ucfirst( $right ) . " " . $type["label_plural"]; ?>
										</div>
										<div class="os-min">
											<input type="checkbox" class="toggle" name="permissions[]" value="<?= slugify( $right . "_" . $type["slug"] ); ?>" <? if ( in_array( slugify( $right . "_" . $type["slug"] ), $permissions ) ) {
														echo "checked";
													} ?>>
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
					<input type="checkbox" name="use_color" class="toggle" value="1" <? if ( $role->use_color ) {
						echo "checked";
					} ?>> Use Role Color
				</div>
				<div class="padb2">
					<input type="color" name="color" value="<?= $role->color; ?>">
				</div>
				<div class="padb2">
					<input type="checkbox" value="1" name="default" <? if ( $role->default ) {
						echo "checked";
					} ?>> Default Role
				</div>
			</div>
		</div>

	<?
	}

	function save( $args ) {
		global $db;
		$id = $this->id;
		$permissions = serialize( $_POST['permissions'] );

		$role = new Role();
		$changed = "created";
		if ( $id > 0 ) {
			$changed = "updated";
			$role->load( "*", array( "id = :id", ":id" => $id ) );
		}

		$default = isset( $_POST['default'] ) ? 1 : 0;
		if ( $default ) {
			$db->exec( "UPDATE $role->table SET `default` = 0 WHERE `default` = 1" );
		}

		$role->slug = $_POST["slug"];
		$role->label = $_POST["label"];
		$role->priority = $_POST["priority"];
		$role->use_color = isset( $_POST["use_color"] ) ? 1 : 0;
		$role->color = $_POST["color"];
		$role->permissions = $permissions;
		$role->default = $default;

		$role->save();

		\Alerts::instance()->success( "Role $role->slug $changed" );
		redirect( "/admin/roles/edit/{$role->id}" );
	}

	function delete( $args ) {
		$id = $args['id'];
		$role = new Role();
		$role->load( "*", array( "id = :id", ":id" => $id ) );
		$role->erase();

		\Alerts::instance()->success( "Role deleted" );
		redirect( "/admin/roles" );
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


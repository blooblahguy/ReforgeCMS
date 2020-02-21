<?
	$core->route("GET /admin/roles", "Roles->index");
	$core->route("GET /admin/roles/edit/@id", "Roles->edit");
	$core->route("POST /admin/roles/save/@id", "Roles->save");
	$core->route("GET /admin/roles/delete/@id", "Roles->delete");

	class Roles extends \Prefab {
		function __construct() {
			global $core;
		}

		function index($core, $args) {
			$core->set("page_title", "Roles & Permissions");
			$core->set("view", "controllers/roles/index.php");
		}
		function edit($core, $args) {
			$core->set("role_id", $args["id"]);
			$core->set("page_title", "%s %s");
			$core->set("view", "controllers/roles/edit.php");
		}
		function save($core, $args) {
			global $db; 
			$id = $args["id"];
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

		function delete($core, $args) {
			$id = $args['id'];
			$role = new Role();
			$role->load("id = $id");
			$role->erase();

			\Alerts::instance()->success("Role deleted");
			redirect("/admin/roles");
		}
	}
?>
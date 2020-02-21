<?
	$core->route("GET /admin/roles", "Roles->index");
	$core->route("GET /admin/roles/edit/@id", "Roles->edit");
	$core->route("POST /admin/roles/save/@id", "Roles->save");

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

		}
	}
?>
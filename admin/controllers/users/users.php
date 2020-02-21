<?
	$core->route("GET {$admin_path}/users", "Users->index");
	$core->route("GET {$admin_path}/users/edit/@id", "Users->edit");
	$core->route("POST {$admin_path}/users/save", "Users->save");

	class Users extends \Prefab {
		function __construct() {
			global $core;

			// $index = ;
			// $edit = "controllers/post_types/edit.php";
		}

		function index($core, $args) {
			// global $alerts;
			// \Alerts::instance()->error("test");
			$core->set("page_title", "Users");
			$core->set("view", "controllers/users/index.php");
		}
		function edit($core, $args) {
			$core->set("post_id", $args["id"]);
			$core->set("page_title", "%s %s");
			$core->set("view", "controllers/users/edit.php");
		}
		function save($core, $args) {
			
		}
	}
?>
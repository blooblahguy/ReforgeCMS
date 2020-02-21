<?
	$core->route("GET /admin/users", "Users->index");
	$core->route("GET /admin/users/edit/@id", "Users->edit");
	$core->route("POST /admin/users/save/@id", "Users->save");

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
			$id = $args["id"];

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
	}
?>
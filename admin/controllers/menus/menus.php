<?
	// $core->route("GET /admin/post_types", "PostTypes->index");
	// $core->route("GET /admin/post_types/edit/@id", "PostTypes->edit");
	// $core->route("POST /admin/post_types/save/@id", "PostTypes->save");

	class Menus extends \Prefab {
		function __construct() {
			global $core;
		}

		function index($core, $args) {
			// $core->set("page_title", "Custom Post Types");
			// $core->set("view", "controllers/post_types/index.php");
		}
		function edit($core, $args) {
			// $core->set("post_id", $args["id"]);
			// $core->set("page_title", "%s %s");
			// $core->set("view", "controllers/post_types/edit.php");
		}
		function save($core, $args) {

		}
	}
?>
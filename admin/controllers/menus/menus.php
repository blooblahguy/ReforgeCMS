<?
	$core->route("GET /admin/menus", "Menus->index");
	$core->route("GET /admin/menus/edit/@id", "Menus->edit");
	$core->route("POST /admin/menus/save/@id", "Menus->save");

	class Menus extends \Prefab {
		function __construct() {
			global $core;
		}

		function index($core, $args) {
			// $core->set("page_title", "Custom Post Types");
			$core->set("view", "controllers/menus/index.php");
		}
		function edit($core, $args) {
			// $core->set("post_id", $args["id"]);
			// $core->set("page_title", "%s %s");
			$core->set("view", "controllers/menus/edit.php");
		}
		function save($core, $args) {

		}
	}
?>
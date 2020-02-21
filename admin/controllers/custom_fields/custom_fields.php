<?
	$core->route("GET /admin/custom_fields", "CustomFields->index");
	$core->route("GET /admin/custom_fields/edit/@id", "CustomFields->edit");
	$core->route("POST /admin/custom_fields/save/@id", "CustomFields->save");

	class CustomFields extends \Prefab {
		function __construct() {
			global $core;
		}

		function index($core, $args) {
			$core->set("page_title", "Custom Fields");
			$core->set("view", "controllers/custom_fields/index.php");
		}
		function edit($core, $args) {
			$core->set("fieldset_id", $args["id"]);
			$core->set("page_title", "%s %s");
			$core->set("view", "controllers/custom_fields/edit.php");
		}
		function save($core, $args) {

		}
	}
?>
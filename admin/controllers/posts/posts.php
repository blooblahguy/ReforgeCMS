<?
	$core->route("GET /", "Views->display_dashboard");
	$core->route("GET /admin/posts/@post_type", "Posts->index");
	$core->route("GET /admin/posts/@post_type/edit/@id", "Posts->edit");
	$core->route("POST /admin/posts/@post_type/save/@id", "Posts->save");

	class Posts extends \Prefab {
		function __construct() {

		}
		function display_dashboard($core, $args) {
			$core->set("view", "controllers/views/dashboard.php");
		}
		function index($core, $args) {
			$core->set("post_type", $args["post_type"]);
			$core->set("view", "controllers/posts/index.php");
		}
		function edit($core, $args) {
			$core->set("post_type", $args["post_type"]);
			$core->set("post_id", $args["id"]);
			$core->set("view", "controllers/posts/edit.php");
		}
		function save($core, $args) {

		}
	}
?>
<?
	$core->route("GET /admin/post_types", "PostTypes->index");
	$core->route("GET /admin/post_types/edit/@id", "PostTypes->edit");
	$core->route("POST /admin/post_types/save/@id", "PostTypes->save");

	class PostTypes extends \Prefab {
		function __construct() {
			global $core;

			// $index = ;
			// $edit = "controllers/post_types/edit.php";
		}

		function index($core, $args) {
			$core->set("page_title", "Custom Post Types");
			$core->set("view", "controllers/post_types/index.php");
		}
		function edit($core, $args) {
			$core->set("post_id", $args["id"]);
			$core->set("page_title", "%s %s");
			$core->set("view", "controllers/post_types/edit.php");
		}
		function save($core, $args) {
			$id = $args["id"];
			$type = new \PostType();
			$changed = "created";
			if ($id > 0) {
				$changed = "updated";
				$type->load("id = $id");
			}

			$slug = $_POST["slug"];
			$label = $_POST["label"];
			$label_plural = $_POST["label_plural"];
			$description = $_POST["description"];
			$icon = $_POST["icon"];
			$admin_menu = $_POST["admin_menu"];
			$order = $_POST["order"];
			$public = $_POST["public"];
			$searchable = $_POST["searchable"];
			$default_status = $_POST["default_status"];

			$statuses = repeater_existing("statuses");
			$new_statuses = repeater_new("new_status", "name", "status");
			$statuses = array_merge($statuses, $new_statuses);
			if (isset($default_status)) {
				$statuses[$default_status]["default_status"] = 1;
			}

			
			// debug($statuses);

			$type->slug = $slug;
			$type->label = $label;
			$type->label_plural = $label_plural;
			$type->description = $description;
			$type->admin_menu = $admin_menu;
			$type->public = $public;
			$type->searchable = $searchable;
			$type->order = $order;
			$type->icon = $icon;
			$type->statuses = serialize($statuses);

			$rs = $type->save();

			\Alerts::instance()->success("Post type $slug $changed");
			redirect("/admin/post_types/edit/{$type->id}");

		}
	}
?>
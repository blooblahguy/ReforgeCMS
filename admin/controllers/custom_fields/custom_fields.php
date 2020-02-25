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

		private function build_hierarchy($source) {
			$nested = array();

			foreach ($source as &$field) {
				if ($field["parent"] == "0") {
					$nested[$field["key"]] = &$field;
				} else {
					$pid = $field["parent"];
					if ( isset($source[$pid]) ) {
						// If the parent ID exists in the source array
						// we add it to the 'children' array of the parent after initializing it.
						if ( !isset($source[$pid]['children']) ) {
							$source[$pid]['children'] = array();
						}
						$source[$pid]['children'][$field["key"]] = &$field;
					}
				}
			}

			return $nested;
		}

		function save($core, $args) {
			$id = $args["id"];
			$cf = new CustomField();
			$changed = "created";
			if ($id > 0) {
				$changed = "updated";
				$cf->load("id = $id");
			}

			$title = $_POST["title"];
			$fields = $_POST['rcf_fields'];
			$fieldset = $this->build_hierarchy($fields);

			debug($fieldset);

			// load options

			// display options			

			$cf->title = $title;
			$cf->fieldset = serialize($fieldset);
			$cf->save();

			\Alerts::instance()->success("Custom Field $title $changed");
			redirect("/admin/custom_fields/edit/{$cf->id}");
		}
	}
?>
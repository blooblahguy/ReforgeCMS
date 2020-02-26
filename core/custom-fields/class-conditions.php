<?

$conditions = array();
$conditions["post_type"] = "admin/posts/after_title";
$conditions["user"] = "admin/user_logged_in";

class rcf_rule {
	var $conditions;

	function __construct() {
		global $core;

		// add_action("admin_before_edit_{$post_type}", array($this, "evaluate_conditions"));
	}

	function evaluate_conditions() {
	}

	function update_values() {
		global $db;
	}

	function get_values($key) {
		if ($key == "post_type") {

		} elseif ($key == "user") {

		} elseif ($key == "form") {

		} elseif ($key == "widget") {

		} elseif ($key == "admin_page") {

		} elseif ($key == "user_role") {

		}
	}
}

?>
<?

$conditions = array();
$conditions["post_type"] = "admin/posts/after_title";
$conditions["user"] = "admin/user_logged_in";

class rcf_rule {
	var $conditions;

	protected function __construct() {
		global $core;

		// add_action($this->action, array($this, "rule_evaluate"));

		RCF::instance()->register_rule_type($this);
	}

	// function rule_evaluate(...$args) {
	// 	// add_action()
	// 	$this->rule_match();
	// }

	protected function update_values() {
		global $db;
	}

	protected function get_values($key) {
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
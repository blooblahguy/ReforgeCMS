<?

class rcf_rule_USER extends rcf_rule {
	function __construct() {
		$this->name = 'user';
		$this->label = "User";
		$this->action = "admin/page/edit_after";
		$this->rule_class = __CLASS__;

		// now register in parent
		parent::__construct();
	}

	function rule_match($request, $rule) {
		return $this->compare($request['user_id'], $rule);
	}

	function rule_choices() {
		global $db;

		$post_types = $db->query("SELECT * FROM users ORDER BY `username` ASC");
		foreach ($post_types as $pt) {
			$choices[$pt["id"]] = $pt['username'];
		}

		return $choices;
	}
}



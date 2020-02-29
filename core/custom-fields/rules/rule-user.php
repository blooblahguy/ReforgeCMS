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

	protected function rule_match($request, $rule, $value) {
		
	}

	protected function rule_choices() {
		global $db;

		$post_types = $db->query("SELECT * FROM users ORDER BY `username` ASC");
		foreach ($post_types as $pt) {
			$choices[$pt["id"]] = $pt['username'];
		}

		return $choices;
	}
}

// REGISTER
new rcf_rule_USER();

?>
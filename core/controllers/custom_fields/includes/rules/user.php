<?php

class rcf_rule_USER extends rcf_rule {
	public
	$name = "",
	$label = "",
	$action = "",
	$rule_class = "";
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

		$users = new User();
		$users = $users->find("id, username");		

		// $post_types = $db->query("SELECT * FROM users ORDER BY `username` ASC");
		foreach ($users as $pt) {
			$choices[$pt["id"]] = $pt['username'];
		}

		return $choices;
	}
}



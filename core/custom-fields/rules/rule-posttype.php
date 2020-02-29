<?

class rcf_rule_POSTTYPE extends rcf_rule {
	function __construct() {
		$this->name = 'post_type';
		$this->label = "Post Type";
		$this->action = "admin/page/edit_after";
		$this->rule_class = __CLASS__;

		// now register in parent
		parent::__construct();
	}

	function rule_match() {

	}

	function rule_choices() {
		global $db;

		$post_types = $db->query("SELECT * FROM post_types ORDER BY `order` ASC");
		foreach ($post_types as $pt) {
			$choices[$pt["id"]] = $pt['label'];
		}

		return $choices;
	}
}

// REGISTER
new rcf_rule_POSTTYPE();

?>
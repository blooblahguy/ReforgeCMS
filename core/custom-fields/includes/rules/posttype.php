<?

class rcf_rule_POSTTYPE extends rcf_rule {
	function __construct() {
		$this->name = 'post_type';
		$this->label = "Admin Page";
		$this->action = "admin/page/edit_after";
		$this->rule_class = __CLASS__;

		// now register in parent
		parent::__construct();
	}

	function rule_match($request, $rule) {
		return $this->compare($request['page_slug'], $rule);
	}

	function rule_choices() {
		global $db;

		$admin_pages = RF_Admin_Pages::instance()->pages;
		foreach ($admin_pages as $slug => $page) {
			$choices[$page->name] = $page->label_plural;
		}

		return $choices;
	}
}

?>
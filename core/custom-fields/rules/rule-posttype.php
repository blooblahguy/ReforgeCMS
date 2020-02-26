<?


class rcf_rule_POSTTYPE extends rcf_rule {
	function __construct() {
		$this->name = 'post_type';
		$this->label = "Post Type";

		// now register in parent
		parent::__construct();
	}
}

?>
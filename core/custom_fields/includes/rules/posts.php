<?

class rcf_rule_POSTS extends rcf_rule {
	function __construct($info) {
		$this->name = $info['name'];
		$this->label = $info['label'];
		// $this->type = $info['type'];
		$this->rule_class = __CLASS__;

		// debug($this->name);

		// now register in parent
		parent::__construct();
	}

	function rule_match($request, $rule) {
		return $this->compare($request['page_id'], $rule);
	}

	function rule_choices() {
		$choices = array();

		$post = new Post();
		$posts = $post->query("SELECT * FROM {$post->table} WHERE post_type = '{$this->name}'");
		foreach ($posts as $p) {
			$choices[$p['id']] = $p['title'];
		}

		return $choices;
	}
}

// $pts = array
global $rf_custom_posts;
add_filter("admin/post_types", function($rf_custom_posts) {
	foreach ($rf_custom_posts as $cp) {
		new rcf_rule_POSTS(array(
			"name" => $cp['slug'],
			"label" => $cp['label'],
		));
	}

	return $rf_custom_posts;
});

?>
<?

class admin_page_POSTS extends RF_Admin_Page {
	function __construct($info) {
		$this->name = $info["name"];
		$this->label = $info["label"];
		$this->label_plural = $info["label_plural"];
		$this->admin_menu = $info["admin_menu"];
		$this->icon = $info["icon"];
		$this->base_permission = $info["base_permission"];
		$this->link = $info["link"];

		parent::__construct();
	}

	function render_index() {
		global $db;

		$this->post_type = $this->name;

		$posts = $db->exec("SELECT * FROM posts WHERE post_type = '{$this->post_type}' ");

		// display table
		display_results_table($posts, array(
			'title' => array(
				"label" => "Title",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'author' => array(
				"label" => "Author",
				"calculate" => function($value, $id) {
					$user = new User();
					$user->load("id = $value", NULL, 10000);
					return $user->username;
				}
			),
			'modified' => array(
				"label" => "Updated",
				"calculate" => function($value, $id) {
					return Date("Y-m", strtotime($value));
				}
			),
			'created' => array(
				"label" => "Created",
				"calculate" => function($value, $id) {
					return Date("Y-m", strtotime($value));
				}
			),
		));	
	}

	function render_edit() {
		$post_type = $this->name;
		$id = $this->id;

		$post = new Post();
		if ($id > 0) {
			$post->load("id = $id");
		}

		?>

		<div class="row">
			<div class="os">
				<div class="content pad2 padl0">
					<?

					render_admin_field($post, array(
						"type" => "text",
						"label" => "Title",
						"name" => "title",
						"required" => true,
					));

					?>
				</div>
			</div>

			<div class="os-2 sidebar pad3">
				<input type="submit" class="marg0" value="Save">
				<hr>
			</div>
		</div>

		<?
	}

	function save_page() {
		$id = $this->id;

		$post = new Post();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$post->load("id = $id");
		}

		$post->title = $_POST["title"];
		$post->post_type = $this->name;
		$post->save();

		$this->save_success($post->title, $changed, $post->id);
	}

	function delete_page() {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	function can_view($args) {

	}

	function can_edit($args) {

	}

	function can_save($args) {

	}

	function can_delete($args) {

	}
	
	*/
}

$cpts = $db->exec("SELECT * FROM post_types WHERE admin_menu = 1 ORDER BY `order` ASC", null, get_cache("post_types"));

foreach ($cpts as $post) {
	$info = array(
		"admin_menu" => 5 + $post["order"],
		"icon" => $post["icon"],
		"name" => $post["slug"],
		"label" => $post["label"],
		"label_plural" => $post["label_plural"],
		"base_permission" => array("update_any_{$post['slug']}", "update_own_{$post['slug']}"),
		"route" => "/admin/posts/@slug",
		"link" => "/admin/posts/{$post['slug']}",
	);

	new admin_page_POSTS($info);
}


?>
<?

class admin_page_POSTS extends admin_page {
	function __construct($info) {

		$this->name = $info["name"];
		$this->label = $info["label"];
		$this->label_plural = $info["label_plural"];
		$this->admin_menu = $info["admin_menu"];
		$this->icon = $info["icon"];
		$this->base_permission = $info["base_permission"];
		$this->link_base = $info["link_base"];

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index($core, $args) {
		global $db; 
		$posts = $db->exec("SELECT * FROM posts WHERE post_type = '{$this->name}' "); 

		display_results_table($posts, array(
			"title" => array(
				"label" => "Title",
				"html" => '<a href="/admin/posts/'.$this->name.'/edit/%2$d">%1$s</a>',
			),
		));
	}

	protected function render_edit($core, $args) {
		$post_type = $this->name;
		$id = $args['id'];

		$post = new Post();
		if ($id > 0) {
			$post->load("id = $id");
		}

		?>

		<div class="row">
			<div class="os">
				<div class="content pad2 padl0">
					<?
					do_action("admin/posts/before_title", $post_type);

					render_admin_field($post, array(
						"type" => "text",
						"label" => "Title",
						"name" => "title",
						"required" => true,
					));

					do_action("admin/posts/after_title", $post_type);
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

	protected function save_page($core, $args) {

	}

	protected function delete_page($core, $args) {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

$cpts = $db->exec("SELECT * FROM post_types WHERE admin_menu = 1 ORDER BY `order` ASC");
foreach ($cpts as $post) {
	new admin_page_POSTS(array(
		"admin_menu" => 5 + $post["order"],
		"icon" => $post["icon"],
		"name" => $post["slug"],
		"link_base" => $post["slug"],
		"label" => $post["label"],
		"label_plural" => $post["label_plural"],
		"base_permission" => array("update_any_{$post['slug']}", "update_own_{$post['slug']}"),
		"link_base" => "/admin/posts/{$post["slug"]}",
	));
	?>
<? } 



?>
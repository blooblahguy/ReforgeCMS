<?

class admin_page_POSTS extends RF_Admin_Page {
	function __construct($info) {
		$this->category = "Content";
		$this->name = $info["name"];
		$this->label = $info["label"];
		$this->label_plural = $info["label_plural"];
		$this->admin_menu = $info["admin_menu"];
		$this->icon = $info["icon"];
		$this->base_permission = $info["base_permission"];
		$this->link = $info["link"];
		$this->is_post = true;

		parent::__construct();
	}

	function render_index() {
		global $db;

		$this->post_type = $this->name;
		$post = new Post();
		$posts = $post->select("*", "post_type = '{$this->post_type}' ");

		$columns = array(
			'title' => array(
				"label" => "Title",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'author' => array(
				"label" => "Author",
				"calculate" => function($value, $id) {
					$user = new User();
					$username = $user->select("username", "id = $value");
					return $username[0]->username;
				}
			),
			'modified' => array(
				"label" => "Updated",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
			'created' => array(
				"label" => "Created",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
		);
		$columns = apply_filters("admin/posts/columns", $columns);

		// display table
		display_results_table($posts, $columns);	
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
						"class" => "post_title",
						"required" => true,
					));

					do_action("admin/custom_fields", "post");
					?>
				</div>
			</div>

			<div class="os-2 sidebar pad3">
				<input type="submit" class="marg0" value="Save">
				<hr>
				<div class="formsec">
					<label for="">Permalink</label>
					<?
					render_admin_field($post, array(
						"type" => "text",
						"name" => "permalink",
						"class" => "post_permalink",
						"required" => true,
					));
					?>
				</div>
			</div>
		</div>

		<?
	}

	function save_page($core, $args) {
		$id = $this->id;
		$user = new User();
		$user->get_current_user();

		$post = new Post();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$post->load("id = $id");
		}

		$metas = $_POST['rcf_meta'];
		RCF()->save_fields("post", $id);

		// poopulate group meta values with # children
		

		// debug($args);

		// debug($data);

		// debug($metas);

		$post->title = $_POST["title"];
		$post->permalink = $_POST["permalink"];
		$post->post_type = $this->name;
		$post->author = $user->id;
		// $post->save();

		// $this->save_success($post->title, $changed, $post->id);
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

$post = new PostType();
$cpts = $post->get_admin_post_pages();

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
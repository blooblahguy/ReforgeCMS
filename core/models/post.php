<?

class Post extends \RF\Mapper {
	public $meta;
	function __construct($id = false) {
		$schema = array(
			"post_type" => array(
				"type" => "VARCHAR(100)"
			),
			"post_parent" => array(
				"type" => "INT(7)",
			),
			"title" => array(
				"type" => "VARCHAR(280)",
			),
			"subtitle" => array(
				"type" => "VARCHAR(512)",
			),
			"content" => array(
				"type" => "LONGTEXT"
			),
			"seo_title" => array(
				"type" => "VARCHAR(70)"
			),
			"seo_category" => array(
				"type" => "VARCHAR(70)"
			),
			"seo_description" => array(
				"type" => "VARCHAR(256)"
			),
			"seo_enable" => array(
				"type" => "INT(1)",
				"attrs" => "NOT NULL DEFAULT 1"
			),
			"disable_comments" => array(
				"type" => "INT(1)"
			),
			"slug" => array(
				"type" => "VARCHAR(190)",
			),
			"author" => array(
				"type" => "INT(7)",
			),
			"post_parent" => array(
				"type" => "INT(7)",
				"attrs" => "NOT NULL DEFAULT 0"
			),
			"post_status" => array(
				"type" => "VARCHAR(250)",
			),
			"permission" => array(
				"type" => "VARCHAR(156)",
			),
			"permission_exp" => array(
				"type" => "VARCHAR(10)",
			),
		);

		parent::__construct("rf_posts", $schema);

		if ($id !== false) {
			$this->load("*", array("id = :id", ":id" => $id));
		}
	}

	function afterinsert() {
		do_action("post/insert", $this);
		do_action("post/insert/{$this->post_type}", $this);
	}

	function is_visible() {
		$user = current_user();
		$visible = false;

		if ($this->author = $user->id) {
			return true;
		}
		
		if ($this->permission) {
			if ($this->permission_exp == "==") {
				if (! $user->can($this->permission)) {
					return false;
				}
			} elseif ($this->permission_exp == "!=") {
				if ($user->can($this->permission)) {
					return false;
				}
			}
		}

		return true;
	}

	function hierchal_permalink($parent_id) {
		$permalinks = array();
		$post = new Post();
		$post->load("*", array("id = :id", ":id" => $parent_id));
		if ($post->post_parent) {
			$permalinks = array_merge($permalinks, $this->hierchal_permalink($post->post_parent));
		}
		$permalinks[] = $post->slug;

		return $permalinks;
	}

	function get_breadcrumbs_recursive($id) {
		$crumbs = array();
		$post = new Post();
		$post->load("id, post_parent, title, slug", array("id = :id", ":id" => $id));

		$crumbs[] = array($post->title, $post->get_permalink());

		if ($post->post_parent) {
			$crumbs = array_merge($crumbs, $this->get_breadcrumbs_recursive($post->post_parent));
		}

		return $crumbs;
	}

	function get_breadcrumbs() {
		$crumbs = array();
		$crumbs[] = array($this->title, $this->get_permalink());

		// debug($this->post_parent);

		if ($this->post_parent) {
			$crumbs = array_merge($crumbs, $this->get_breadcrumbs_recursive($this->post_parent));
		}

		$home = get_option("site_homepage");
		$crumbs[] = array("Home", "/");

		// debug($crumbs);
		
		return $crumbs;
	}

	function get_permalink() {
		if ($this->permalink) {
			return $this->permalink;
		} 

		// if ($id == 0 && ! $this->id) { 
		// 	return false;
		// }

		$permalinks = array();

		// load a post if ID is > 0
		// if ($id > 0) {
		// 	$this->load("*", array("id = :id", ":id" => $id));
		// }

		$home = get_option("site_homepage");
		if ($this->id == $home) {
			return "/";
		}

		// get post type, and check for url prefix
		$pt = new PostType();
		$pt->load("*", array("slug = :post_type", ":post_type" => $this->post_type));
		if ($pt->allow_parents == 0 && $pt->url_prefix != "") {
			$permalinks[] = $pt->url_prefix;
		}

		// if we don't have a post parent, then just return our slug link
		if (! $this->post_parent) {
			$permalinks[] = $this->slug;

			// $this->permalink = "/".implode("/", $permalinks);
			
			return "/".implode("/", $permalinks);
		}
		
		$permalinks = array_merge($permalinks, $this->hierchal_permalink($this->post_parent));
		$permalinks[] = $this->slug;
		
		// $this->permalink = "/".implode("/", $permalinks);
		return "/".implode("/", $permalinks); 
	}

	function load_meta() {
		$meta = get_meta($this->id);
		$this->meta = $meta;
	}
}

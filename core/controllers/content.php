<?

	class Page {
		public $post = array();

		function __construct() {

		}

		
	}

	class Content extends Prefab {
		private $content = array();
		public $page;

		// automatic header
		function beforeroute($core, $args) {
			$this->page = $this->content[$args[0]];
			locate_template(array("functions.php"), true, false);
			locate_template(array("header.php"), true, true);
		}
		// automatic footer
		function afterroute($core, $args) {
			$this->page = $this->content[$args[0]];
			locate_template(array("footer.php"), true, true);
		}

		// create content class
		function __construct() {
			global $core;
			global $db;

			$pages = $db->exec("SELECT posts.* FROM posts 
				LEFT JOIN post_types ON posts.post_type = post_types.slug
				WHERE post_types.public = 1 
			");
			$home = get_option("site_homepage");
			foreach ($pages as $post) {
				$page = new Page();
				$page->post = $post;


				if ($post['id'] == $home) {
					$this->content["/"] = $page;
					$core->route("GET /", "Content->home");
				} else {
					$this->content[$post['permalink']] = $page;
					$core->route("GET /{$post['permalink']}", "Content->page");
				}
			}
		}

		function home($core, $args) {
			$templates = array();
			$templates[] = "home.php";
			$templates[] = "page.php";
			$templates[] = "single.php";
			$templates[] = "index.php";

			locate_template($templates, true);
		}

		function page($core, $args) {
			// debug($args);
			// add_action("admin/render_view");
			// $core->set("view", "page.php");
		}
	}

	function theme_url() {
		return "/content/themes/".get_option("active_theme")."/";
	}

	function theme_path() {
		return $_SERVER['DOCUMENT_ROOT']."/content/themes/".get_option("active_theme")."/";
	}

	function get_template_part($slug, $name = '', $include = true) {
		$templates = array();
		if ($name !== '') {
			$templates[] = "{$slug}-{$name}.php";
		}
		$templates[] = "{$slug}.php";

		return locate_template( $templates, $include, false );
	}

	function rf_include($path) {
		// set globals for each file
		$user = current_user();
		$page = Content::instance()->page;

		include($path);
	}


	function locate_template($templates, $include = true, $required = false) {
		

		$path = theme_path();
		$looked = "";
		foreach ($templates as $t) {
			$looked .= $t.", ";
			if (file_exists($path.$t)) {
				if ($include) {
					rf_include($path.$t);
				} else {
					return $path.$t;
				}
				return;
			}
		}
		$looked = rtrim($looked, ", ");

		if ($required) {
			Alerts::instance()->error("Theme doesn't have required theme file: $looked");
		}
	}
?>
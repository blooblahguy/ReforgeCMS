<?
	$request = array();

	class Page extends Magic {
		private $model_schema;
		protected $fields = array();
		protected $data;

		function exists($key) {
			return array_key_exists($key,$this->data);
		}

		function set($key, $val) {
			$this->data[$key] = $val;
		}

		function &get($key) {
			return $this->data[$key];
		}

		function clear($key) {
			unset($this->data[$key]);
		}

		function __construct() {
			$post = new Post();
			$this->model_schema = $post->model_schema;
		}

		function set_object($object) {
			foreach ($this->model_schema as $key => $info) {
				$this->$key = $object[$key];
			}

			if (isset($object['id'])) {
				$this->id = $object['id'];
			}
			if (isset($object['created'])) {
				$this->created = $object['created'];
			}
			if (isset($object['modified'])) {
				$this->modified = $object['modified'];
			}
		}

		function get_fields() {
			
		}
	}

	class Content extends Prefab {
		private $pages = array();
		public $page;

		// automatic header
		function beforeroute($core, $args) {
			$this->page = $this->pages[$args[0]];

			locate_template(array("functions.php"), true, false);
			locate_template(array("header.php"), true, true);
		}
		// automatic footer
		function afterroute($core, $args) {
			$this->page = $this->pages[$args[0]];
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
				$page->set_object($post);

				if ($post['id'] == $home) {
					$this->pages["/"] = $page;
					$core->route("GET /", "Content->home");
				} else {
					$this->pages["/".$post['permalink']] = $page;
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
			$templates = array();
			$templates[] = "page-{$this->page->slug}.php";
			$templates[] = "page-{$this->page->id}.php";
			$templates[] = "page.php";
			$templates[] = "single.php";

			locate_template($templates, true);
		}
	}

	function theme_url() {
		return "/content/themes/".get_option("active_theme");
	}

	function theme_path() {
		return $_SERVER['DOCUMENT_ROOT']."/content/themes/".get_option("active_theme")."/";
	}

	function get_template_part($slug, $name = '', $include = true) {
		$templates = array();
		if ($name !== '') {
			$templates[] = "{$slug}-{$name}.php";
			$templates[] = "{$slug}/{$name}.php";
		}
		$templates[] = "{$slug}.php";

		return locate_template( $templates, $include, false );
	}

	function rf_require($path) {
		// set globals for each file
		$user = current_user();
		$page = Content::instance()->page;

		require $path;
	}


	function locate_template($templates, $include = true, $required = false) {
		$path = theme_path();
		$looked = "";
		foreach ($templates as $t) {
			$looked .= $t.", ";
			if (file_exists($path.$t)) {
				if ($include) {
					rf_require($path.$t);
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
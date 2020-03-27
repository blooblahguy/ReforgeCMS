<?
	$request = array();

	class Page extends Magic {
		private $schema;
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
			$this->schema = $post->schema;
		}

		function factory($object) {
			foreach ($this->schema as $key => $info) {
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
				$page->factory($post);

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

			add_body_class("page");

			locate_template($templates, true);
		}
	}

	function rf_head() {
		global $request, $options;

		if ($options['disable_seo']) {
			echo '<meta name="robots" content="noindex" />';
		}

		$parts = array();
		$seperator = $options['seo_seperator'];
		$sitename = $options['sitename'];
		$title = $request['page']['seo_title'];
		$category = $request['page']['seo_category'];
		$description = $request['page']['seo_description'];

		$parts[] = $title;
		if ($category != '') {
			$parts[] = $category;
		}
		$parts[] = $sitename;

		$title = implode(" $seperator ", $parts);

		// Title and description
		echo "<title>$title</title>";
		echo "<meta name='description' content='$description'>";

		// echo out SEO
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<meta name=viewport content="width=device-width, initial-scale=1">';

		//
	}

	function body_classes() {
		global $request;
		if (! $request['body_classes']) {
			$request['body_classes'] = array();
		}
		echo 'class="'.implode(" ", $request['body_classes']).'"';
	}

	function add_body_class($class) {
		global $request;
		if (! $request['body_classes']) {
			$request['body_classes'] = array();
		}
		if (! in_array($class, $request['body_classes'])) {
			$request['body_classes'][] = $class;
		}
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
		global $request;
		$user = current_user();
		$page = Content::instance()->page;

		$request['user_id'] = $user->id;
		$request['page_id'] = $page->id;

		// populate out basic fields
		if ($request['page_id'] > 0) {
			$request['page'] = array();
			$request['page']["id"] = $request['page_id'];
			$p = new Post();
			$p->load("id = {$request['page_id']}");
			foreach ($p->schema as $k => $f) {
				$request['page'][$k] = $p->{$k};
			}
		}

		add_body_class(slugify($page->title));
		
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


	function get_menu($slug) {
		global $request;

		$menu = new Menu;
		$menu->load(array("slug = :slug", ":slug" => $slug));

		return $menu->get_menu_array();
	}

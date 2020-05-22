<?

function rf_head() {
	global $request, $options, $core;

	if ($core->ERROR) {
		$request['page'] = array(
			"seo_title" => $core->ERROR['status'],
			"description" => "",
			"seo_noindex" => "1",
		);
	}

	if ($options['disable_seo'] || $request['page']['seo_noindex']) {
		echo '<meta name="robots" content="noindex" />';
	}

	$parts = array();
	$seperator = $options['seo_seperator'];
	$sitename = $options['sitename'];

	$title = $request['page']['seo_title'];
	$title = apply_filters("page/title", $title, $request, $core->PARAMS);

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
}

function rf_footer() {
	rf_scripts();
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

function get_params() {
	global $core;
	return $core->PARAMS;
}

function rf_require($path) {
	global $request;
	$user = current_user();
	$page = Content::instance()->page;
	$params = get_params();
	
	$request['user_id'] = $user->id;
	$request['page_id'] = $page->id;
	$request['page'] = $page;

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
			return false;
		}
	}
	$looked = rtrim($looked, ", ");

	

	if ($required) {
		Alerts::instance()->error("Theme doesn't have required theme file: $looked");
	}
}


function get_menu($slug) {
	$menu = new Menu;
	$menu->load(array("slug = :slug", ":slug" => $slug));

	return $menu->get_menu_array();
}


function the_page() {
	global $request;

	return $request['page'];
}

function get_content() {
	$page = the_page();
	$content = apply_filters("get_content", $page['content'], $page);
	$content = parse_shortcodes($content);

	return $content;
}

function the_content() {
	echo get_content();
}


class Content extends \Prefab {
	public $pages = array();
	public $page;

	function url_parse($args) {
		$info = $args;
		$base = $info[0];
		unset($info[0]);

		foreach ($info as $key => $value) {
			$base = str_replace("/$value", "", $base);
		}

		return $base;
	}

	// automatic header
	function beforeroute($core, $args) {
		$url = $this->url_parse($args);
		$this->page = isset($this->page) ? $this->page : $this->pages[$url];

		locate_template(array("functions.php"), true, false);
		locate_template(array("header.php"), true, true);
	}
	// automatic footer
	function afterroute($core, $args) {
		$url = $this->url_parse($args);
		$this->page = isset($this->page) ? $this->page : $this->pages[$url];

		locate_template(array("footer.php"), true, true);
	}

	// create content class
	function __construct() {
		global $core;
		$user = current_user();
		$pages = get_pages();
		$home = get_option("site_homepage");

		$this->pages['error'] = new Post();
		$this->pages['error']->title = "Error";

		// $core->set('ONERROR', "Content->error");

		foreach ($pages as $post) {
			$page = $this->add_page($post);
			// debug($page);
			if ($page->permission) {
				if ($page->permission_exp == "==") {
					if (! $user->can($page->permission)) {
						continue;
					}
				} elseif ($page->permission_exp == "!=") {
					if ($user->can($page->permission)) {
						continue;
					}
				}
			}
			
			if ($post['id'] == $home) {
				$this->pages["/"] = $page;
				$core->route("GET /", "Content->home");
			} else {
				$permalink = $page->get_permalink();
				$this->pages[$permalink] = $page;
				$core->route("GET {$permalink}", "Content->page");
			}
		}
	}

	function error($core, $args) {
		global $root; 

		$error = $core->ERROR;
		$page = locate_template(array($error['code'].".php"), false);
		if ($page) {
			require $page;
		} else {
			require $root."/core/error.php";
		}
	}

	function add_page($info) {
		$page = new \Post();
		$page->factory($info);
		$permalink = $page->get_permalink();

		// debug($info);

		$this->pages[$permalink] = $page;

		return $page;
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

	function query($post_type, $args = array()) {
		$posts = new Post();
		$posts = $posts->find("post_type = '$post_type'", array(
			"order by" => "created DESC"
		));

		return $posts;
	}
}

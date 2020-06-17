<?

/**
 * Render breadcrumb array
 */
function render_breadcrumbs($crumbs, $sep = "&raquo;") {
	$crumbs = array_reverse($crumbs);
	$last = end($crumbs);
	reset($crumbs);

	?>
	<div class="breadcrumbs">
		<? foreach ($crumbs as $link) {
			list($name, $href) = $link;
		
			echo '<a class="crumb" href="'.$href.'">'.$name.'</a>';
			if ($link != $last) {
				echo '<span>'.$sep.'</span>';
			}
		}
		?>
	</div>
	<?
}

function rf_head() {
	global $request, $options, $core;

	if ($core->ERROR) {
		$request['page'] = array(
			"seo_title" => $core->ERROR['status'],
			"description" => "",
			"seo_enable" => 0,
		);
	}

	$page = $request['page'];

	if ($options['disable_seo'] || $page['seo_enable'] == 0) {
		echo '<meta name="robots" content="noindex" />';
	}

	$parts = array();
	$seperator = $options['seo_seperator'];
	$title_keywords = $options['seo_keywords'];
	$sitename = $options['sitename'];

	$title = $page['seo_title'];
	$title = apply_filters("page/title", $title, $request, $core->PARAMS);

	$category = $page['seo_category'];
	$description = $page['seo_description'];

	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	$seo_image = get_file(get_field("seo-default-image", "settings_0"))['original'];

	if ($title) {
		$parts[] = $title;
	}
	if ($category != '') {
		$parts[] = $category;
	}
	$parts[] = $sitename;
	$parts[] = $title_keywords;

	$title = implode(" $seperator ", $parts);

	// Title and description
	echo "<title>$title</title>";
	echo "<meta name='description' content='$description'>";

	// echo out SEO
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name=viewport content="width=device-width, initial-scale=1">';

	echo '<meta property="og:title" content="'.$title.'" />';
	echo '<meta property="og:description" content="'.$description.'" />';
	echo '<meta property="og:image" content="'.$url.$seo_image.'" />';

	echo '<meta name="twitter:card" content="summary" /> ';
	echo '<meta name="twitter:site" content="@bdgg" /> ';
	echo '<meta name="twitter:title" content="'.$title.'" /> ';
	echo '<meta name="twitter:description" content="'.$description.'" /> ';
	echo '<meta name="twitter:image" content="'.$url.$seo_image.'" />';
}

function rf_footer() {
	do_action("rf_footer");
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
		if (is_file($path.$t)) {
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


function the_title($id = false) {
	echo get_title($id);
}
function get_title($id = false) {
	global $request;

	if (! $id) {
		$title = $request['page']['title'];
	} else {
		$post = new Post();
		$post->load("*", array("id = :id", ":id" => $id));
		$title = $post->title;
	}

	return $title;
}

function the_author($id = false) {
	echo get_author($id);
}
function get_author($id = false) {
	global $request;

	if (! $id) {
		$author_id = $request['page']['author'];
	} else {
		$post = new Post();
		$post->load("*", array("id = :id", ":id" => $id));
		$author_id = $post->author;
	}

	$author = new User();
	$author->load("*", array("id = :id", ":id" => $author_id));

	return "<span class='strong {$author->class}'>{$author->username}</span>";
}

function the_date($id = false) {
	echo get_date($id);
}
function get_date($id = false) {
	global $request;

	if (! $id) {
		$date = $request['page']['created'];
	} else {
		$post = new Post();
		$post->load("*", array("id = :id", ":id" => $id));
		$date = $post->created;
	}

	return Date("F jS, g:ia", strtotime($date));
}

function get_menu($slug) {
	$menu = new Menu;
	$menu->load("*", array("slug = :slug", ":slug" => $slug));

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

		foreach ($pages as $post) {
			$page = $this->add_page($post);
			if (! $page->is_visible()) {
				continue;
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
		$page = new Post();

		$page->factory($info);
		$permalink = $page->get_permalink();
		// return $page;

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
		$templates[] = "{$this->page->post_type}-{$this->page->slug}.php";
		$templates[] = "{$this->page->post_type}-{$this->page->id}.php";
		$templates[] = "{$this->page->post_type}.php";
		$templates[] = "single.php";

		// debug($this->page);

		add_body_class("page");

		locate_template($templates, true);
	}

	function query($post_type, $args = array()) {
		$posts = new Post();
		$posts = $posts->find("*", "post_type = '$post_type'", $args);

		return $posts;
	}
}
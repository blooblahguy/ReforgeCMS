<?

	class Content {
		function __construct() {
			global $core;
			global $db;
			global $options;

			$pages = $db->exec("SELECT * FROM posts");
			foreach ($pages as $post) {
				$core->route("GET {$post['permalink']}", "Content->page");
			}

			// if (isset($options['home_page'])) {

			// }

			$core->route("GET /", "Content->home");
			// $core->route("GET /*", "Content->page");
		}

		function home($core, $args) {
			// debug($args);
			// add_action("admin/render_view");
			$core->set("view", "home.php");
		}

		function page($core, $args) {
			// debug($args);
			// add_action("admin/render_view");
			$core->set("view", "page.php");
		}
	}
?>
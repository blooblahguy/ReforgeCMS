<?

	class Content {
		function __construct() {
			global $core;

			$core->route("GET /", "Content->home");
			$core->route("GET /@page", "Content->page");
		}

		function home($core, $args) {
			// add_action("admin/render_view");
			$core->set("view", "home.php");
		}

		function page($core, $args) {
			// add_action("admin/render_view");
			$core->set("view", "page.php");
		}
	}
?>
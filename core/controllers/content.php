<?

	class Content {
		function __construct() {
			global $core;

			$core->route("GET /", "Content->home");
			$core->route("GET /@page", "Content->page");
		}

		function home($core, $args) {
			$core->set("view", "home.php");
		}

		function page($core, $args) {
			$core->set("view", "page.php");
		}
	}
?>
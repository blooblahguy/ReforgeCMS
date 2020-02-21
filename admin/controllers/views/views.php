<?
	class Views extends \Prefab {
		function __construct() {

		}
		function display_dashboard($core, $args) {
			$core->set("view", "controllers/views/dashboard.php");
		}
	}
?>
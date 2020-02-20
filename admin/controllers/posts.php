<?
	class Posts extends \Prefab {
		function __construct() {

		}
		function display_dashboard() {
			global $views;
			$views["content"] = "dashboard.php";
		}
	}
?>
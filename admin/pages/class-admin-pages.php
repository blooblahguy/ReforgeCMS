<?

class admin_pages extends \Prefab {
	public $pages = array();

	function register_page($class) {
		$pages[ $class->name ] = $class;
	}

	function render_admin_menu() {
		global $admin_menu;


	}
}

// Include Files
require_once("class-admin-page.php");
require_once("pages/page-comments.php");
require_once("pages/page-custom-fields.php");
require_once("pages/page-dashboard.php");
require_once("pages/page-forms.php");
require_once("pages/page-menus.php");
require_once("pages/page-post-types.php");
require_once("pages/page-posts.php");
require_once("pages/page-roles.php");
require_once("pages/page-settings.php");
require_once("pages/page-users.php");
require_once("pages/page-widgets.php");

?>
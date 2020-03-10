<?

class RF_Admin extends \Prefab {
	private $pages = array();

	function __construct() {}
}

// Include Files
require_once("pages/class-admin-page.php");
require_once("pages/class-admin-pages.php");
require_once("pages/pages/page-comments.php");
require_once("pages/pages/page-custom-fields.php");
require_once("pages/pages/page-dashboard.php");
require_once("pages/pages/page-forms.php");
require_once("pages/pages/page-menus.php");
require_once("pages/pages/page-post-types.php");
require_once("pages/pages/page-posts.php");
require_once("pages/pages/page-roles.php");
require_once("pages/pages/page-settings.php");
require_once("pages/pages/page-users.php");
require_once("pages/pages/page-widgets.php");
require_once("pages/pages/page-plugins.php");
require_once("pages/pages/page-themes.php");

$admin = new RF_Admin;

?>
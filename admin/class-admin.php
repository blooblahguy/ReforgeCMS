<?

class RF_Admin extends Prefab {
	private $pages = array();

	function __construct() {}
}

// Include Files
include("pages/class-admin-page.php");
include("pages/class-admin-pages.php");
include("pages/pages/page-comments.php");
include("pages/pages/page-custom-fields.php");
include("pages/pages/page-dashboard.php");
include("pages/pages/page-forms.php");
include("pages/pages/page-menus.php");
include("pages/pages/page-post-types.php");
include("pages/pages/page-posts.php");
include("pages/pages/page-roles.php");
include("pages/pages/page-settings.php");
include("pages/pages/page-users.php");
include("pages/pages/page-widgets.php");
include("pages/pages/page-plugins.php");
include("pages/pages/page-themes.php");

$admin = new RF_Admin;

?>
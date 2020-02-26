<?

class admin_pages extends \Prefab {
	public $pages = array();

	function register_page($class) {
		global $admin_menu;
		$this->pages[ $class->name ] = $class;

		// add to user menu
		if (isset($class->admin_menu) && $class->admin_menu !== false) {
			$admin_menu[$class->admin_menu] = array(
				"label" => $class->label_plural,
				"icon" => $class->icon,
				"link" => $class->link_base,
			);
		}
	}

	function render_admin_menu() {
		global $admin_menu, $PATH;

		list($admin, $controller, $action) = explode("/", $PATH);
		$menu_path = "$admin/$controller";
		if ($action) {
			$menu_path_action = "$admin/$controller/$action";
		}

		// add seperators
		$admin_menu[29] = array("type" => "seperator");
		$admin_menu[69] = array("type" => "seperator");

		ksort($admin_menu);
		foreach ($admin_menu as $menu) {
			list($admin, $controller, $action) = explode("/", trim($menu["link"], "/"));
			$menu_key = "$admin/$controller";
			if ($action) {
				$menu_key = "$admin/$controller/$action";
			}

			if ($menu["type"]) { 
				echo "<span></span>"; 
			} else { ?>
				<a href="<?= $menu["link"]; ?>" <? if ($menu_key == $menu_path || $menu_key == $menu_path_action) { echo 'class="active"'; }?>><i><?= $menu["icon"]; ?></i> <?= $menu["label"]; ?></a>
			<? }
		}
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
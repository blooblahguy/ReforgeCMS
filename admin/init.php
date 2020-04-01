<?

// Include Files
require "$root/admin/pages/class-admin-page.php";
require "$root/admin/pages/class-admin-pages.php";


function build_pages() {
	global $root;
	
	$pages = array();
	$pages["comments"] = "page-comments.php";
	$pages["customfields"] = "page-custom-fields.php";
	$pages["dashboard"] = "page-dashboard.php";
	// $pages["forms"] = "page-forms.php";
	$pages["media"] = "page-media.php";
	$pages["menus"] = "page-menus.php";
	$pages["posttypes"] = "page-post-types.php";
	$pages["posts"] = "page-posts.php";
	$pages["roles"] = "page-roles.php";
	$pages["settings"] = "page-settings.php";
	$pages["users"] = "page-users.php";
	$pages["partials"] = "page-partials.php";
	$pages["plugins"] = "page-plugins.php";
	$pages["themes"] = "page-themes.php";

	$pages = apply_filters("admin/pages", $pages);

	foreach ($pages as $class => $path) {
		require "$root/admin/pages/pages/$path";
	}
}

add_action("admin/init", "build_pages", 20);
add_action("admin/init", array(RF_Admin_Pages::instance(), "build_menus"), 30);
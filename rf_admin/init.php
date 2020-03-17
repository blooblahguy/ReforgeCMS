<?

// Include Files
require "pages/class-admin-page.php";
require "pages/class-admin-pages.php";

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
// $pages["plugins"] = "page-plugins.php";
$pages["themes"] = "page-themes.php";

foreach ($pages as $class => $path) {
	require "pages/pages/$path";
}


?>
<?

	$schema = new \DB\Schema($db);

	require_once("users.php");
	require_once("roles.php");
	require_once("roles_permissions.php");
	require_once("post_types.php");
	require_once("post_metas.php");
	require_once("posts.php");
	require_once("options.php");
	require_once("comments.php");

	// Ensure database structure
	$schema->setup();
?>
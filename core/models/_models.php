<?
	$root = dirname(__FILE__);
	$cache = \Cache::instance();
	$last_updated = $cache->get("schema_last_updated");

	$files = array();
	$files[] = "$root/users.php";
	$files[] = "$root/roles.php";
	$files[] = "$root/post_types.php";
	$files[] = "$root/custom_fields.php";
	$files[] = "$root/post_metas.php";
	$files[] = "$root/posts.php";
	$files[] = "$root/options.php";
	$files[] = "$root/comments.php";
	$files[] = "$root/menus.php";

	// cached updating
	$update = false;
	$this_mod = filemtime(__FILE__);
	foreach ($files as $file) {
		if (filemtime($file) > $last_updated || $this_mod > $last_updated) {
			$update = true;
			break;
		}
	}

	if ($update) {
		$cache->set("schema_last_updated", time());
		require_once("$root/../schema.php");
		$schema = new RFSchema($db);

		foreach ($files as $file) {
			require_once($file);
		}

		// Ensure database structure
		$schema->setup();
	}	
?>
<?
	$schema->add("posts", array(
		"post_type" => array(
			"type" => "VARCHAR(100)"
		),
		"post_parent" => array(
			"type" => "INT(7)",
		),
		"title" => array(
			"type" => "VARCHAR(256)",
		),
		"content" => array(
			"type" => "LONGTEXT"
		),
		"seo_title" => array(
			"type" => "LONGTEXT"
		),
		"seo_description" => array(
			"type" => "LONGTEXT"
		),
		"permalink" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"author" => array(
			"type" => "INT(7)",
		),
		"post_status" => array(
			"type" => "VARCHAR(256)",
		),
	));
?>
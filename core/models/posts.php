<?
	$schema->add("posts", array(
		"post_type" => array(
			"type" => "VARCHAR(100)"
		),
		"title" => array(
			"type" => "VARCHAR(256)",
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
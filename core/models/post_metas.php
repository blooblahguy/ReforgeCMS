<?
	$schema->add("posts_meta", array(
		"post_id" => array(
			"type" => "INT(7)",
		),
		"meta_type" => array(
			"type" => "VARCHAR(256)",
		),
		"meta_key" => array(
			"type" => "VARCHAR(256)",
		),
		"meta_value" => array(
			"type" => "LONGTEXT",
		),
	));
?>
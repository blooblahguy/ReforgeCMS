<?
	$schema->add("posts", array(
		"post_type" => array(
			"type" => "VARCHAR(100)"
		),
		"slug" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"post_status" => array(
			"type" => "VARCHAR(256)",
		),
	));
?>
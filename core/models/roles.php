<?
	$schema->add("roles", array(
		"slug" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"label" => array(
			"type" => "VARCHAR(256)"
		),
		"permissions" => array(
			"type" => "LONGTEXT"
		),
		"post_type_permissions" => array(
			"type" => "LONGTEXT"
		),
		"use_color" => array(
			"type" => "INT(1)"
		),
		"color" => array(
			"type" => "VARCHAR(7)"
		),
		"order" => array(
			"type" => "INT(3)"
		),
		"priority" => array(
			"type" => "INT(3)",
			"unique" => true
		)
	));
?>
<?
	$schema->add("post_types", array(
		"slug" => array(
			"type" => "VARCHAR(256)",
			"unique" => true,
		),
		"label" => array(
			"type" => "VARCHAR(256)"
		),
		"label_plural" => array(
			"type" => "VARCHAR(256)"
		),
		"description" => array(
			"type" => "LONGTEXT"
		),
		"public" => array(
			"type" => "INT(1)",
			"default" => 1
		),
		"searchable" => array(
			"type" => "INT(1)",
			"default" => 1
		),
		"icon" => array(
			"type" => "VARCHAR(100)",
			"default" => "create"
		),
		"admin_menu" => array(
			"type" => "INT(1)"
		),
		"order" => array(
			"type" => "INT(3)",
			"default" => 10
		),
		"statuses" => array(
			"type" => "LONGTEXT"
		),
	));
?>
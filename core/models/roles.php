<?
	$schema->add("roles", array(
		"slug" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"label" => array(
			"type" => "VARCHAR(256)"
		),
		"menu_order" => array(
			"type" => "INT(3)"
		)
	));
?>
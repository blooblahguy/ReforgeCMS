<?
	$schema->add("menus", array(
		"slug" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"label" => array(
			"type" => "VARCHAR(256)"
		),
		"links" => array(
			"type" => "LONGTEXT"
		)
	));
?>
<?
	$schema->add("options", array(
		"key" => array(
			"type" => "VARCHAR(256)",
			"unique" => true
		),
		"value" => array(
			"type" => "LONGTEXT"
		)
	));
?>
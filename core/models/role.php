<?



class Role extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"slug" => array(
				"type" => "VARCHAR(190)",
				"unique" => true
			),
			"label" => array(
				"type" => "VARCHAR(256)"
			),
			"permissions" => array(
				"type" => "LONGTEXT"
			),
			"use_color" => array(
				"type" => "INT(1)"
			),
			"color" => array(
				"type" => "VARCHAR(7)"
			),
			"locked" => array(
				"type" => "INT(1)"
			),
			"default" => array(
				"type" => "INT(1)",
			),
			"priority" => array(
				"type" => "INT(3)",
			),
			"display_seperately" => array(
				"type" => "INT(1)",
			)
		);

		parent::__construct("rf_roles", $schema);
	}
}


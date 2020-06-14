<?


class Meta extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"meta_parent" => array(
				"type" => "INT(7)",
			),
			"meta_type" => array(
				"type" => "VARCHAR(255)",
			),
			"meta_key" => array(
				"type" => "VARCHAR(255)",
			),
			"meta_value" => array(
				"type" => "LONGTEXT",
			),
			"meta_info" => array(
				"type" => "VARCHAR(100)",
			),
			"created" => false,
			"modified" => false,
		);

		parent::__construct("rf_meta", $schema);
	}
}

// class Meta2 extends \RF\Mapper {
// 	function __construct() {
// 		$schema = array(
// 			"type" => array(
// 				"type" => "VARCHAR(100)",
// 			),
// 			"parent" => array(
// 				"type" => "INT(7)",
// 			),
// 			"meta_parent" => array(
// 				"type" => "INT(7)",
// 			),
// 			"key" => array(
// 				"type" => "VARCHAR(255)",
// 			),
// 			"value" => array(
// 				"type" => "LONGTEXT",
// 			),
// 			"info" => array(
// 				"type" => "VARCHAR(100)",
// 			),
// 			"created" => false,
// 			"modified" => false,
// 		);

// 		parent::__construct("rf_meta2", $schema);
// 	}
// }



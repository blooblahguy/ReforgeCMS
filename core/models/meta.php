<?php


class Meta extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"meta_parent" => array(
				"type" => "INT(7)",
			),
			"meta_type" => array(
				"type" => "VARCHAR(255)",
			),
			"meta_group" => array(
				"type" => "INT(10)",
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
			"meta_order" => array(
				"type" => "INT(10)",
			),
			"created" => false,
			"modified" => false,
		);

		parent::__construct("rf_meta", $schema);
	}
}

// custom fields -> 
// 	title
// 	load_priority
// 	load_rules

// post
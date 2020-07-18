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
			"field_key" => array(
				"type" => "VARCHAR(255)",
			),
			"field_value" => array(
				"type" => "LONGTEXT",
			),
			"field_parent" => array(
				"type" => "VARCHAR(100)",
			),
			"field_info" => array(

			),
			"created" => false,
			"modified" => false,
		);

		parent::__construct("rf_meta", $schema);
	}
}
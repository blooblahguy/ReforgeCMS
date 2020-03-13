<?php

class RF_File extends RF_Model {
	function __construct() {
		$this->model_table = "rf_media";
		$this->model_schema = array(
			"hash" => array(
				"type" => "VARCHAR(32)",
				"unique" => true
			),
			"name" => array(
				"type" => "VARCHAR(256)"
			),
			"original" => array(
				"type" => "VARCHAR(256)"
			),
			"sizes" => array(
				"type" => "LONGTEXT"
			)
		);

		parent::__construct();
	}
}

?>
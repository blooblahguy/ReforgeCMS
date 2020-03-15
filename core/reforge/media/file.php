<?php

class RF_File extends RF_Model {
	function __construct() {
		$this->model_table = "rf_media";
		$this->model_schema = array(
			"hash" => array(
				"type" => "VARCHAR(32)",
				"unique" => true
			),
			"type" => array(
				"type" => "VARCHAR(100)"
			),
			"extension" => array(
				"type" => "VARCHAR(100)"
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

	/**
	 * Create ID Image
	 */
	function create_id_img($string) {
		global $core;
		// Create image from string
		$img = new Image();
		$img = $img->identicon($string, 100, 3);
		$name = "{$string}_avatar_auto.png";

		// Now set file upload path
		$path = uploads_dir().$name;
		$url = uploads_url().$name;

		// Save in Media
		$core->write($path, $img->dump("png", 9));

		return $url;
	}
}

?>
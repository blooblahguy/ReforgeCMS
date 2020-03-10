<?

	class Role extends RF_Model {
		function __construct() {
			$this->model_table = "roles";
			$this->model_schema = array(
				"slug" => array(
					"type" => "VARCHAR(256)",
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
					"unique" => true
				),
				"display_seperately" => array(
					"type" => "INT(1)",
				)
			);

			parent::__construct( );
		}
	}

?>
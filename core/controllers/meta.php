<?
	class Meta extends RF_Model {
		function __construct() {
			$this->model_table = "post_meta";
			$this->model_schema = array(
				"parent_id" => array(
					"type" => "INT(7)",
				),
				"meta_type" => array(
					"type" => "VARCHAR(256)",
				),
				"meta_key" => array(
					"type" => "VARCHAR(256)",
				),
				"meta_value" => array(
					"type" => "LONGTEXT",
				),
			);

			parent::__construct( );
		}
	}
?>
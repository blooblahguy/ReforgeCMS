<?
	class Meta extends RF_Model {
		function __construct() {
			$this->model_table = "menus";
			$this->model_schema = array(
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
			);

			parent::__construct( );
		}
	}
?>
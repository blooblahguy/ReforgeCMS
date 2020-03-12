<?
	class Meta extends RF_Model {
		function __construct() {
			$this->model_table = "post_meta";
			$this->model_schema = array(
				"meta_parent" => array(
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
				"meta_info" => array(
					"type" => "VARCHAR(100)",
				),
			);
			$this->disable_created = true;
			$this->disable_modified = true;

			parent::__construct();
		}
	}
?>
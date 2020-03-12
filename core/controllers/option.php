<?

	class Option extends RF_Model {
		function __construct() {
			$this->model_table = "options";
			$this->model_schema = array(
				"key" => array(
					"type" => "VARCHAR(256)",
					"unique" => true
				),
				"value" => array(
					"type" => "LONGTEXT"
				)
			);

			parent::__construct();
		}

		function load_all() {
			$options = $this->select("*");
			$options = rekey_array("key", $options);

			return $options;
		}
	}

?>
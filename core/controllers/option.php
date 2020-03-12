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
			$options = $this->query("SELECT * FROM {$this->model_table}");
			$options = array_extract($options, "key", "value");

			return $options;
		}
	}

	function get_option($key) {
		global $options;
		return $options[$key];
	}
	function set_option($key, $value = "") {
		global $options;
		$option = new Option();
		if ($options[$key]) {
			$option->load("`key` = '{$key}'");
		}

		$option->key = $key;
		$option->value = $value;
		$option->save();

		$options[$key] = $value;
	}
?>
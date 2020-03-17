<?

	class Option extends RF_Model {
		function __construct() {
			$this->model_table = "options";
			$this->model_schema = array(
				"key" => array(
					"type" => "VARCHAR(190)",
					"unique" => true
				),
				"value" => array(
					"type" => "LONGTEXT",
					"default" => 0,
					"nullable" => false,
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
		var_dump($key, $options[$key]);
		var_dump(isset($options[$key]));
		$option = new Option();
		if (isset($options[$key])) {
		debug($key, $value);
			$option->load("`key` = '{$key}'");
			// debug()
		}


		$option->key = $key;
		$option->value = $value;
		$option->save();

		$options[$key] = $value;
	}
?>
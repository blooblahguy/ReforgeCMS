<?

	class Option extends \RF\Mapper {
		function __construct() {
			$schema = array(
				"key" => array(
					"type" => "VARCHAR(190)",
				),
				"value" => array(
					"type" => "LONGTEXT",
				)
			);

			parent::__construct("rf_options", $schema);
		}

		function load_all() {
			$options = $this->find();
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

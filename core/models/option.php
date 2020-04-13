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
		if (is_serial($options[$key])) {
			return unserialize($options[$key]);
		}
		return $options[$key];
	}
	function set_option($key, $value = "") {
		global $options;

		if (is_array($value)) {
			$value = serialize($value);
		}

		$option = new Option();
		if (isset($options[$key])) {
			// don't bother updating if the values are the same
			if ($options[$key] == $value) {
				return;
			}
			$option->load("`key` = '{$key}'");
		}
		
		
		$option->key = $key;
		$option->value = $value;
		$option->save();

		$options[$key] = $value;
	}

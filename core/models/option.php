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
		$options = $this->find("*");
		$options = array_extract($options, "key", "value");

		return $options;
	}
}
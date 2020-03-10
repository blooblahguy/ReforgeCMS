<?

	class Option extends RF_Model {
		function __construct() {
			$this->model_table = "options";

			parent::__construct();
		}

		function load_all() {
			global $db;
			$options = $db->exec("SELECT * FROM {$this->model_table}");
			$options = rekey_array("key", $options);

			return $options;
		}
	}

?>
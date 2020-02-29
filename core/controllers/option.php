<?

	class Option extends RF_Model {
		function __construct() {
			$this->model_table = "options";

			parent::__construct();
		}

		function load_all() {
			global $db;
			$cached = $this->get_cache($this->sl_name);
			$options = $db->exec("SELECT * FROM options", null, $cached);
			$options = rekey_array("key", $options);
			$this->set_cache($this->sl_name);

			return $options;
		}
	}

?>
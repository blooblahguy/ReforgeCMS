<?

	class CustomField extends RF_Model {
		function __construct() {
			$this->model_table = 'custom_fields';

			parent::__construct();
		}

		function get_fields() {
			if ($this->fieldset) {
				return unserialize($this->fieldset);
			} else {
				return array();
			}
		}

		function get_rules() {
			if ($this->load_rules) {
				return unserialize($this->load_rules);
			} else {
				return array();
			}
		}

		function load_all() {
			global $db;
			// debug($this->caches["fields"]);
			// $cached = $this->get_cache($this->caches["fields"]);
			$cfs = $db->exec("SELECT * FROM `{$this->model_table}`", null, $cached);
			$cfs = rekey_array("id", $cfs);
			// $this->set_cache($this->caches["fields"]);

			return $cfs;
		}
	}

?>
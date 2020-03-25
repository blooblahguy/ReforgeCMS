<?

	class CustomField extends RF_Model {
		protected $cfs_load = array();

		function __construct() {
			$this->model_table = 'custom_fields';
			$this->model_schema = array(
				"title" => array(
					"type" => "VARCHAR(255)",
				),
				"first_rule" => array(
					"type" => "VARCHAR(255)"
				),
				"load_rules" => array(
					"type" => "LONGTEXT",
				),
				"fieldset" => array(
					"type" => "LONGTEXT",
				),
				"active" => array(
					"type" => "INT(1)",
				),
			);

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

			if (count($this->cfs_load) == 0) {
				$cfs = $db->exec("SELECT * FROM `{$this->model_table}`", null);
				$cfs = rekey_array("id", $cfs);
				foreach ($cfs as $cf) {
					$fieldset = unserialize($cf['fieldset']);
					RCF()->store_field_data($fieldset);
				}
				$this->cfs_load = $cfs;
			}

			return $this->cfs_load;
		}
	}

?>
<?

	class CustomField extends \RF\Mapper {
		protected $cfs_load = array();

		function __construct() {
			$schema = array(
				"title" => array(
					"type" => "VARCHAR(255)",
				),
				"priority" => array(
					"type" => "INT(1)",
					"nullable" => false,
					"default" => 0
				),
				"load_rules" => array(
					"type" => "LONGTEXT",
				),
				"fieldset" => array(
					"type" => "LONGTEXT",
				),
				"inactive" => array(
					"type" => "INT(1)",
				),
			);

			parent::__construct("custom_fields", $schema);
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
				$cfs = $this->find(null, array("order by" => "priority DESC"));
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
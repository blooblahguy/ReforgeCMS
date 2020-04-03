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
					"attrs" => "NOT NULL DEFAULT 0",
				),
				"load_rules" => array(
					"type" => "LONGTEXT",
				),
				"fieldset" => array(
					"type" => "LONGTEXT",
				),
				"virtual" => array( // virtual fields aren't editable in the normal UI and are attached directly to specific objects, i.e forms
					"type" => "INT(1)",
					"attrs" => "NOT NULL DEFAULT 0",
				),
				"inactive" => array(
					"type" => "INT(1)",
					"attrs" => "NOT NULL DEFAULT 0",
				),
			);

			parent::__construct("rf_custom_fields", $schema);
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


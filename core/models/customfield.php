<?

class CustomField extends \RF\Mapper {
	protected $cfs_load = array();

	function __construct() {
		$schema = array(
			"parent_id" => array(
				"type" => "INT(7)",
			),
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


	private function build_hierarchy($source) {
		$nested = array();
		if (! isset($source)) { return array(); }

		foreach ($source as &$field) {
			if ($field["parent"] == "0") {
				$nested[$field["key"]] = &$field;
			} else {
				$pid = $field["parent"];
				if ( isset($source[$pid]) ) {
					// If the parent ID exists in the source array
					// we add it to the 'children' array of the parent after initializing it.
					if ( !isset($source[$pid]['children']) ) {
						$source[$pid]['children'] = array();
					}
					$source[$pid]['children'][$field["key"]] = &$field;
				}
			}
		}

		return $nested;
	}

	function save_fieldset() {
		$title = $_POST["title"];
		$fields = $_POST['rcf_fields'];
		$fieldset = $this->build_hierarchy($fields);

		// LOAD RULES
		$load_conditions = $_POST["load_conditions"];
		$rules = array();
		if (isset($load_conditions)) {
			foreach ($load_conditions as $group => $conditions) {
				$set = array();
				foreach ($conditions["key"] as $id => $value) {
					$set[$id]["key"] = $value;
				}
				foreach ($conditions["expression"] as $id => $value) {
					$set[$id]["expression"] = $value;
				}
				foreach ($conditions["value"] as $id => $value) {
					$set[$id]["value"] = $value;
				}

				$rules[$group] = $set;
			}		
		}

		$this->title = $title;
		$this->priority = $_POST['priority'];
		$this->inactive = $_POST['inactive'];
		$this->fieldset = serialize($fieldset);
		$this->load_rules = serialize($rules);
		$this->virtual = $_POST['virtual_fieldset'];
		$this->save();

		return $this;
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
			$cfs = $this->find("*", null, array("order by" => "priority DESC"));
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


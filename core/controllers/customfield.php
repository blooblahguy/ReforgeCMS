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
	}

?>
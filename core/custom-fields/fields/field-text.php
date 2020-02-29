<?

class reforge_field_TEXT extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "text";
		$this->label = "Text";
		$this->category = "Content";
		$this->defaults = array();

		// now register in parent
		parent::__construct();
	}

	// FIELD ADMIN HTML
	function render_field($field) {
		
	}

	// RENDER ADMIN FIELD EDITING
	function render_field_settings($field) {
		// Default Value
		rcf_render_field_setting($field, array(
			"label" => "Default Value",
			"type" => "text",
			"name" => "default_value",
			"placeholder" => "Default Value",
		));

		// Placeholder
		rcf_render_field_setting($field, array(
			"label" => "Placeholder",
			"type" => "text",
			"name" => "placeholder",
			"placeholder" => "Placeholder",
		));
	}

	// VALIDATE
	function validate_value($valid, $value, $field, $input_name) {
		$valid = true;

		return $valid;
	}
}

// REGISTER
new reforge_field_TEXT();

?>
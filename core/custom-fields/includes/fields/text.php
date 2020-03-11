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

	//========================================================
	// EDIT
	//========================================================
	function html($data, $field) {
		$parent = $field['parent'];
		$key = $field['key'];
		
		render_admin_field($data, array(
			"type" => $field['type'],
			"label" => $field['label'],
			"name" => "meta[$parent][$key]",
			"required" => $field['required'],
			"placeholder" => $field['placeholder'],
		));
	}


	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
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
}

?>
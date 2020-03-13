<?

class reforge_field_IMAGE extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "image";
		$this->label = "Image";
		$this->category = "Content";
		$this->defaults = array();

		// now register in parent
		parent::__construct();
	}

	//========================================================
	// EDIT
	//========================================================
	function html($data, $field) {
		render_admin_field($data, array(
			"type" => $field['type'],
			"label" => $field['label'],
			"name" => $data["name"],
			"required" => $field['required'],
			"placeholder" => $field['placeholder'],
		));
	}


	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
		
		// Layout
		rcf_render_field_setting($field, array(
			"label" => "Layout",
			"type" => "select",
			"name" => "layout",
			"choices" => array(
				"os-12" => "Full",
				"os-9" => "3/4",
				"os-8" => "2/3",
				"os-6" => "1/2",
				"os-4" => "1/3",
				"os-3" => "1/4"
			)
		));
	}
}

?>
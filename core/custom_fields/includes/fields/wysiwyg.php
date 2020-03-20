<?

class reforge_field_WYSIWYG extends reforge_field {

	function __construct() {
		$this->name = "wysiwyg";
		$this->label = "WYSIWYG";
		$this->category = "Content";

		parent::__construct();
	}


	//========================================================
	// EDIT
	//========================================================
	function html($data, $field) {
		// debug($data);
		// debug($field);
		render_admin_field($data, array(
			"type" => "wysiwg",
			"label" => $field['label'],
			"name" => $data["name"],
			"required" => $field['required'],
			"placeholder" => $field['placeholder'],
			"height" => $field['height'],
		));
	}



	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
		// Layout
		rcf_render_field_setting($field, array(
			"label" => "Height",
			"type" => "number",
			"name" => "height",
			"default" => "300",
			// "placeholder" => "Placeholder",
		));
		// Layout
		rcf_render_field_setting($field, array(
			"label" => "Layout",
			"type" => "select",
			"name" => "layout",
			"choices" => array(
				"os-12" => "Full",
				"os" => "Auto Fit",
				"os-min" => "Minimum",
				"os-9" => "3/4",
				"os-8" => "2/3",
				"os-6" => "1/2",
				"os-4" => "1/3",
				"os-3" => "1/4",
			)
		));
	}

	function prepare_value($data) {
		// debug($data);
		// exit();
		$data['meta_value'] = htmlspecialchars_decode($data['meta_value']);

		return $data;
	}

	function prepare_save($meta, $all_metas) {
		$meta['meta_value'] = htmlspecialchars($meta['meta_value']);

		return $meta;
	}
}

?>
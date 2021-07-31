<?php

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
		render_html_field($data, array(
			"type" => "wysiwyg",
			"label" => $field['label'],
			"name" => $data["name"],
			"required" => $field['required'],
			"placeholder" => $field['placeholder'],
			"style" => "height: {$field['height']}px",
		));
	}



	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
		// Layout
		render_rcf_field($field, array(
			"label" => "Height",
			"type" => "number",
			"name" => "height",
			"default" => "300",
			// "placeholder" => "Placeholder",
		));
		// Layout
		render_rcf_field($field, array(
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

	function prepare_value($value) {
		return htmlspecialchars_decode($value);
	}

	function prepare_save($meta, $all_metas) {
		$meta['meta_value'] = htmlspecialchars($meta['meta_value']);

		return $meta;
	}
}


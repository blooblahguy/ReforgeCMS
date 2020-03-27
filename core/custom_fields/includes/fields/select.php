<?

class reforge_field_SELECT extends reforge_field {

	function __construct() {
		$this->name = "select";
		$this->label = "Select";
		$this->category = "Choice";

		parent::__construct();
	}


	//========================================================
	// EDIT
	//========================================================
	function html($data, $field) {
		// var_dump($field);
		$choices = array();
		$options = explode("\n", $field['choices']);

		foreach ($options as $opt) {
			list($key, $value) = explode(" : ", trim($opt));
			if (! $value) { $value = $key; }
			if (! $value) { continue; }
			$choices[trim($key)] = trim($value);
		}

		// $data[$data["name"]] = unserialize($value);
		if ($field['select_multiple']) {
			$value = $data[$data["name"]];
			$data[$data["name"]."[]"] = unserialize($value);

			render_admin_field($data, array(
				"type" => "select",
				"label" => $field['label'],
				"name" => $data["name"]."[]",
				"required" => $field['required'],
				"choices" => $choices,
				"multiple" => true,
				"instructions" => "Hold ctrl to select multiple values",
			));
		} else {
			render_admin_field($data, array(
				"type" => "select",
				"label" => $field['label'],
				"name" => $data["name"],
				"required" => $field['required'],
				"choices" => $choices,
			));
		}
	}

	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
		
		rcf_render_field_setting($field, array(
			"label" => "Choices",
			"type" => "textarea",
			"name" => "choices",
			"placeholder" => "Enter each option on a new line. You can also format each line as value : Label",
		));

		?>
		<div class="os">
			<?
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
			// Default
			rcf_render_field_setting($field, array(
				"label" => "Select multible",
				"type" => "checkbox",
				"name" => "select_multiple",
			));
			// Default
			rcf_render_field_setting($field, array(
				"label" => "Default Value",
				"type" => "text",
				"name" => "default_value",
				"placeholder" => "Default Value",
			));
			?>
		</div>
		<?
	}

	function prepare_save($meta, $metas) {
		if (gettype($meta['meta_value']) == "array") {
			$meta['meta_value'] = serialize($meta['meta_value']);
		}

		return $meta;
	}
}

?>
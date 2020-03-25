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
			list($key, $value) = explode(" : ", $opt);
			if (! $value) { $value = $key; }
			$choices[$key] = $value;
		}

		render_admin_field($data, array(
			"type" => "select",
			"label" => $field['label'],
			"name" => $data["name"],
			"required" => $field['required'],
			"choices" => $choices,
		));
	}



	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {
		// Layout
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
				"label" => "Default Value",
				"type" => "text",
				"name" => "default_value",
				"placeholder" => "Default Value",
			));
			?>
		</div>
		<?
	}
}

?>
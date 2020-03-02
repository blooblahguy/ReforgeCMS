<?

class reforge_field_REPEATER extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "repeater";
		$this->label = "Repeater";
		$this->category = "Layout";
		$this->defaults = array(
			'sub_fields'	=> array(),
			'min'			=> 0,
			'max'			=> 0,
			'layout' 		=> 'table',
			'button_label'	=> ''
		);

		// now register in parent
		parent::__construct();
	}

	// FIELD ADMIN HTML
	function render_field($field) {
		
	}

	// RENDER ADMIN FIELD EDITING
	function render_field_settings($field) {
		// Get Subfields First
		$args = array(
			'fields'	=> $field['children'],
			'parent'	=> $field['key']
		);

		?>
		<div class="fieldset sub_fields <?= $field["key"]; ?>">
			<div class="row g1 padx1">
				<div class="os-1">
					<label for="" class="pady1">Sub Fields</label>
				</div>
				<div class="os sub_fields">
					<? rcf_get_view('group-settings', $args); ?>
				</div>
			</div>
		</div>
		<?

		// Minimum Rows
		rcf_render_field_setting($field, array(
			"type" => "number",
			"label" => "Minimum Rows",
			"name" => "min",
			"placeholder" => "0",
		));

		// Maximum Rows
		rcf_render_field_setting($field, array(
			"type" => "number",
			"label" => "Maximum Rows",
			"name" => "max",
			"placeholder" => "0",
		));

		// Button Label
		rcf_render_field_setting($field, array(
			"type" => "text",
			"label" => "Button Label",
			"name" => "button_label",
			"placeholder" => "Add Row",
		));
	}

	// VALIDATE
	function validate_value($valid, $value, $field, $input_name) {
		$valid = true;

		return $valid;
	}
}

// REGISTER
new reforge_field_REPEATER();

?>
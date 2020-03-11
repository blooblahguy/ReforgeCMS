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
	function html($data, $field) {

		// Get children
		$children = array(
			'fields' => $field['children'],
			'source' => $data,
			'parent' => $field['key']
		);

		debug($children);

		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";

		?>

		<div class="repeater_content">
			<label for=""><?= $field['label']; ?></label>
			<div class="sub_fields border pad2">
				<? debug($data); ?>
				<? rcf_get_view('group-fields', $children); ?>
			</div>
			<div class="footer pad1">
				<a href="#" class="btn btn-sm pull-right cf-add" data-target=".sub_fields"><?= $field['button_label']; ?></a>
				<div class="clear"></div>
			</div>
		</div>

		<? $clone = array(
			"key" => "\$key",
			"parent" => "\$parent"
		); ?>

		<div class="<?= $field['key']; ?>">
			template
			<? rcf_get_view('group-field', array( 'data' => array(), 'field' => $clone, 'i' => 0 )); ?>
		</div>
		<?

		// load view
		// rcf_get_view('group-fields', $children);
	}

	// RENDER ADMIN FIELD EDITING
	function options_html($field) {
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


}

?>
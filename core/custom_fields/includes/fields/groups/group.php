<?

class reforge_field_GROUP extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "group";
		$this->label = "Group";
		$this->category = "Layout";

		// now register in parent
		parent::__construct();
	}

	// FIELD ADMIN HTML
	function html($data, $field, $context) {
		// collect children first, to make developing visually easier
		$children = $field['children'];
		unset($field['children']);
		// field is not ready for any layout
		if (! isset($children)) { return; }

		$source = RCF()->current_data;
		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";
		$friendly = str_replace("[]", "", $data['name']);
		$friendly = str_replace("[", "_", $friendly);
		$friendly = str_replace("]", "", $friendly);

		?>
		<div class="rcf_group">
			<label for=""><?= $field['label']; ?></label>
			<div class="row g1">
				<?			
				foreach ($children as $field) {
					$key = $context;
					$key .= "_".$field["slug"];

					$data = $source[$key];

					
					rcf_get_template('group-field', array(
						'field' => $field,
						'context' => $key,
						"data" => $data,
					));
					
				}
				?>
			</div>
		</div>
		<?
	}

	// RENDER ADMIN FIELD EDITING
	function options_html($field) {
		// Get Subfields First
		$args = array(
			'fields' => $field['children'],
			'parent' => $field['key'],
			'post_id' => $field['post_id']
		);

		?>

		<div class="os-12 sub_fields <?= $field["key"]; ?>">
			<label for="" class="">Fields</label>
			<div class="sub_fields">
				<? rcf_get_template('group-settings', $args); ?>
			</div>
		</div>
		
		<?
	}



	function prepare_save($meta, $metas) {
		$key = $meta['meta_key'];

		// count the repeater children and store that as the value
		if (! isset($meta['meta_value'])) {
			$children = 0;
			foreach ($metas as $name => $sub_m) {
				preg_match('/('.$key.')(?:_)([0-9])(_.*)/', $name, $matches);
				// debug($matches);
				if ($matches[2] != "") {
					$children = max($children, (int) $matches[2] + 1);
				}
			}
			$meta['meta_value'] = $children;
			$metas[$key]['meta_value'] = $children;
		}

		return $meta;
	}
}

?>
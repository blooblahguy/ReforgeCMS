<?

class reforge_field_REPEATER extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "repeater";
		$this->label = "Repeater";
		$this->category = "Layout";

		// now register in parent
		parent::__construct();
	}

	function result($data, $field, $context) {
		$index = 0;
		// collect children first, to make developing visually easier
		$children = $field['children'];
		unset($field['children']);

		// field is not ready for any layout
		if (! isset($children)) { return; }

		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";
		$friendly = str_replace("[]", "", $data['name']);
		$friendly = str_replace("[", "_", $friendly);
		$friendly = str_replace("]", "", $friendly);

		?>

		<div class="repeater_outer">
			<label for=""><?= $field['label']; ?></label>
			<div class="repeater_body repeater_<?= $friendly; ?>">
				<?
				// loop through data to populate children layouts
				// debug($children);
				for ($i = 0; $i < $data['meta_value']; $i++) {
					rcf_get_template('group-results', array(
						'fields' => $children,
						"context" => $context,
						"index" => $i
					));
					$index++;
				}
				?>
			</div>

			
			<template class="template_<?= $friendly; ?>">
				<? 
				$template = array( 
					'fields' => $children,
					"context" => $context,
					"index" => "\${$field['key']}index",
					"template" => true,
				);

				rcf_get_template('group-results', $template); 
				?>
			</template>
		</div>

		<?
	}

	// FIELD ADMIN HTML
	function html($data, $field, $context) {
		$index = 0;
		// collect children first, to make developing visually easier
		$children = $field['children'];
		unset($field['children']);

		// field is not ready for any layout
		if (! isset($children)) { return; }

		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";
		$friendly = str_replace("[]", "", $data['name']);
		$friendly = str_replace("[", "_", $friendly);
		$friendly = str_replace("]", "", $friendly);

		?>

		<div class="repeater_outer">
			<label for=""><?= $field['label']; ?></label>
			<div class="repeater_body repeater_<?= $friendly; ?>">
				<?
				// loop through data to populate children layouts
				// debug($children);
				for ($i = 0; $i < $data['meta_value']; $i++) {
					rcf_get_template('group-fields', array(
						'fields' => $children,
						"context" => $context,
						"index" => $i
					));
					$index++;
				}
				?>
			</div>

			
			<div class="repeater_footer border bg-light-grey pad1">
				<div class="pull-left"><? $this->render_instructions($field); ?></div>
				<div class="btn-primary pull-right" data-rcf-template=".template_<?= $friendly ; ?>" data-replace="<?= $field['key']; ?>index" data-index="<?= $index; ?>" data-target=".repeater_<?= $friendly ; ?>"><?= $field['button_label']; ?></div>
				<div class="clear"></div>
			</div>
			<template class="template_<?= $friendly; ?>">
				<? 
				$template = array( 
					'fields' => $children,
					"context" => $context,
					"index" => "\${$field['key']}index",
					"template" => true,
				);

				rcf_get_template('group-fields', $template); 
				?>
			</template>
		</div>

		<?

	}

	// RENDER ADMIN FIELD EDITING
	function options_html($field) {

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

		// Get Subfields First
		$args = array(
			'fields' => $field['children'],
			'parent' => $field['key'],
			'post_id' => $field['post_id']
		);

		?>

		<div class="os-12 sub_fields <?= $field["key"]; ?>">
			<label for="" class="">Sub Fields</label>
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


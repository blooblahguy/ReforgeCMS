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
	function html($data, $field, $context) {
		$index = 0;
		// collect children first, to make developing visually easier
		$children = $field['children'];
		unset($field['children']);

		// field is not ready for any layout
		if (! isset($children)) { return; }

		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";

		?>

		<div class="cf_repeater row">
			<div class="os-min bg-light-grey order pad1" style="min-height: 100%; "></div>
			<div class="os repeater_<?= $context; ?> pad1">
				<div class="row g1 content-middle">
					<div class="os-min dragger"><i>drag_indicator</i></div>
					<label for="" class="os"><?= $field['label']; ?></label>
				</div>
				<div class="repeater_body">
					<?
					// loop through data to populate children layouts
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
			</div>
			<div class="repeater_footer bg-light-grey pad1 os-12 text-right">
				<div class="btn btn-sm" data-template=".<?= $context; ?>_template" data-replace="index" data-index="<?= $index; ?>" data-target=".repeater_<?= $context; ?>"><?= $field['button_label']; ?></div>
			</div>
			<template class="<?= $context; ?>_template">
				<? 
				$template = array( 
					'fields' => $children,
					"context" => $context,
					"index" => "\$index"
				);

				rcf_get_template('group-fields', $template); ?>
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

?>
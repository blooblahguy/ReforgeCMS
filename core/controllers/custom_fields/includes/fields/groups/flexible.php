<?

class reforge_field_FLEXIBLE extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "flexible";
		$this->label = "Flexible";
		$this->category = "Layout";

		// now register in parent
		parent::__construct();
	}

	// FIELD ADMIN HTML
	function html($data, $field, $context) {
		// collect children first, to make developing visually easier
		$templates = $field['children'];
		unset($field['children']);
		// field is not ready for any layout
		if (! isset($templates)) { return; }

		// build templates from children
		foreach ($templates as $child) { ?>
			<template class="<?= $context; ?>_template_<?= $child['slug']; ?>">
				<? 
				$template = array( 
					'fields' => array($child),
					"context" => $context,
					"index" => "\${$field['key']}index",
					"template" => true,
				);

				rcf_get_template('group-fields', $template); ?>
			</template>
			<?
		}

		// Now rebuild children array to only what is active
		$source = RCF()->current_data;
		$children = array();
		foreach ($templates as $k => $child) {
			for ($i = 0; $i < $data['meta_value']; $i++) {
				$key = $context;
				$key .= "_{$i}_";
				$key .= $child["slug"];

				if (isset($source[$key])) {
					$children[$i] = $child;
				}
			}
		}

		ksort($children);
		$index = count($children);
		$field['button_label'] = $field['button_label'] != "" ? $field['button_label'] : "Add Row";
		$friendly = str_replace("[]", "", $data['name']);
		$friendly = str_replace("[", "_", $friendly);
		$friendly = str_replace("]", "", $friendly);
		?>

		<div class="cf_flexible row">
			<div class="os">
				<div class="flexible_body flexible_<?= $friendly; ?>">
					<?
					foreach ($children as $i => $child) {
						rcf_get_template('group-fields', array(
							'fields' => array($child),
							"context" => $context,
							"index" => $i
						));
					}
					?>
				</div>
			</div>
			<div class="flexible_footer border bg-light-grey pad1 os-12">
				<div class="pull-left"><? $this->render_instructions($field); ?></div>
				<? foreach ($templates as $child) { ?>
					<div class="btn-primary pull-right flexible_add" data-rcf-template=".<?= $context; ?>_template_<?= $child['slug']; ?>" data-replace="<?= $field['key']; ?>index" data-index="<?= $index; ?>" data-target=".flexible_<?= $friendly; ?>">Add <?= $child['label']; ?></div>
				<? } ?>
				<div class="clear"></div>
			</div>
		</div>

		<?

	}

	// RENDER ADMIN FIELD EDITING
	function options_html($field) {

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
			<label for="" class="">Content Types</label>
			<div class="sub_fields">
				<? rcf_get_template('group-settings', $args); ?>
			</div>
		</div>
		
		<?
	}



	function prepare_save($meta, $metas) {
		$key = $meta['meta_key'];

		// debug($metas);

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

		// debug($metas);
		// debug($meta);
		// exit();

		return $meta;
	}
}


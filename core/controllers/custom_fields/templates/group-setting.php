<?
	$key = $field["key"];
	$parent = $field["parent"];
	$field['post_id'] = isset($field['post_id']) ? $field['post_id'] : $post_id;

	$meta = array(
		'key' => $field['key'],
		'parent' => $field['parent'],
		'menu_order' => $i,
	);

?>

<div class="rcf_field menu_header sort rcf_field_<?= $key; ?>" data-key="<?= $key; ?>" data-parent="<?= $parent; ?>" data-post_id="<?= $field['post_id']; ?>">
	<div class="meta">
		<input type="hidden" name="rcf_fields[<?= $key?>][key]" value="<?= $meta['key']; ?>" >
		<input type="hidden" name="rcf_fields[<?= $key?>][parent]" value="<?= $meta['parent']; ?>" >
		<input type="hidden" name="rcf_fields[<?= $key?>][menu_order]" value="<?= $meta['menu_order']; ?>" >
	</div>
	<a href="#" data-accordion=".accordion.settings_<?= $key; ?>" class="row accordion_handle content-middle">
		<div class="os-min text-grey self-middle dragger"><i class="display-block">drag_indicator</i></div>
		<div data-value="rcf_fields[<?= $key; ?>][label]" class="os label pad1 strong">(no label)</div>
		<div data-value="rcf_fields[<?= $key; ?>][slug]" class="os slug pad1">(no slug)</div>
		<div data-value="rcf_fields[<?= $key; ?>][type]" class="os type pad1">text</div>
		<div class="os-2 type"><span data-remove=".rcf_field_<?= $key; ?>" class="remove pad1">Remove</span></div>
	</a>
	<div class="accordion pad2 rcf_field_settings collapsed settings_<?= $key; ?>">
		<div class="section field_base">
			<div class="row g1 content-middle">
				<?
					// label
					render_rcf_field($field, array(
						'label' => 'Field Label',
						'name' => 'label',
						'type' => 'text',
						'data-bind' => true,
						'class' => 'field-label',
					));

					// Slug
					render_rcf_field($field, array(
						'label' => 'Slug',
						'name' => 'slug',
						'type' => 'text',
						'data-bind' => true,
						'class' => 'field-slug',
					));

					// Types
					render_rcf_field($field, array(
						'label' => 'Type',
						'name' => 'type',
						'type' => 'select',
						'data-bind' => true,
						'default' => "text",
						'class' => 'field-type rcf_dropdown loaded',
						'choices' => rcf_get_field_types(),
					));
					if (! $field['children']) {
						// Required
						render_rcf_field($field, array(
							'label' => 'Required',
							'name' => 'required',
							'type' => 'checkbox',
							'class' => 'field-required text-center',
							"layout" => "os-min",
						));
					}
				?>
				<?
					// Instructions
					render_rcf_field($field, array(
						'label' => 'Instructions',
						'name' => 'instructions',
						'type' => 'text',
						'class' => 'field-instructions'
					));

					do_action("rcf/options_html/type={$field['type']}", $field);
				?>
			</div>
		</div>

			

	</div>
</div>
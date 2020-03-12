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

<div class="rcf_field rcf_field_<?= $key; ?>" data-key="<?= $key; ?>" data-parent="<?= $parent; ?>" data-post_id="<?= $field['post_id']; ?>">
	<div class="meta">
		<input type="hidden" name="rcf_fields[<?= $key?>][ID]" value="<?= $meta['id']; ?>" >
		<input type="hidden" name="rcf_fields[<?= $key?>][key]" value="<?= $meta['key']; ?>" >
		<input type="hidden" name="rcf_fields[<?= $key?>][parent]" value="<?= $meta['parent']; ?>" >
		<input type="hidden" name="rcf_fields[<?= $key?>][menu_order]" value="<?= $meta['menu_order']; ?>" >
	</div>
	<a href="#" data-accordion=".accordion.settings_<?= $key; ?>" class="row accordion_handle content-middle border">
		<div data-value="rcf_fields[<?= $key; ?>][label]" class="os label pad1">(no label)</div>
		<div data-value="rcf_fields[<?= $key; ?>][slug]" class="os slug pad1">(no slug)</div>
		<div data-value="rcf_fields[<?= $key; ?>][type]" class="os type pad1">text</div>
		<div class="os-2 type"><span data-remove=".rcf_field_<?= $key; ?>" class="remove pad1">Remove</span></div>
	</a>
	<div class="accordion rcf_field_settings collapsed settings_<?= $key; ?>">
		<?
			// label
			rcf_render_field_setting($field, array(
				'label' => 'Field Label',
				'instructions' => 'This is the name which will appear on the EDIT page',
				'name' => 'label',
				'type' => 'text',
				'bind' => true,
				'class' => 'field-label'
			));

			// Slug
			rcf_render_field_setting($field, array(
				'label' => 'Slug',
				'instructions' => 'This is the name which will appear on the EDIT page',
				'name' => 'slug',
				'type' => 'text',
				'bind' => true,
				'class' => 'field-slug'
			));

			// Types
			rcf_render_field_setting($field, array(
				'label' => 'Type',
				'instructions' => 'This is the name which will appear on the EDIT page',
				'name' => 'type',
				'type' => 'select',
				'bind' => true,
				'class' => 'field-type loaded rcf_dropdown',
				'choices' => rcf_get_field_types()
			));

			// Render the field
			echo '<div class="field_settings">';
				do_action("rcf/options_html", $field);
				do_action("rcf/options_html/type={$field['type']}", $field);
			echo '</div>';

			// Required
			rcf_render_field_setting($field, array(
				'label' => 'Required',
				'instructions' => 'This is the name which will appear on the EDIT page',
				'name' => 'required',
				'type' => 'checkbox',
				'class' => 'field-required'
			));

			// Instructions
			rcf_render_field_setting($field, array(
				'label' => 'Instructions',
				'instructions' => 'This is the name which will appear on the EDIT page',
				'name' => 'instructions',
				'type' => 'text',
				'class' => 'field-instructions'
			));
		
		?>	
	</div>
</div>
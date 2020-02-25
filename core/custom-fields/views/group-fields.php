<?
	// debug($fields);
	// echo "render group fields";
?>

<div class="rcf_group_fields">
	<div class="fieldset_outer row">
		<div class="os label strong pad1">Label</div>
		<div class="os slug strong pad1">Slug</div>
		<div class="os type strong pad1">Type</div>
		<div class="os-2 type strong pad1">Actions</div>
		<div class="os-12 cf_fields">
			<? 
			if ($fields) {
				foreach( $fields as $i => $field ) {
					rcf_get_view('group-field', array( 'field' => $field, 'i' => $i ));
				} 
			}
			?>
		</div>
	</div>
	<div class="fieldset_footer pad1 margt1 bg-light-grey">
		<a href="#" class="btn pull-right cf-add" data-target=".cf_fields" data-parent="<?= $parent; ?>">+ Add Field</a>
		<div class="clear"></div>
	</div>
</div>

<? $clone = array(
	"key" => "\$key",
	"parent" => "\$parent"
); ?>

<template class="blank_field">
	<? rcf_get_view('group-field', array( 'field' => $clone, 'i' => 0 )); ?>
</template>
<?
	$id = $core->get("post_id");
	$cf = new CustomField();
	$action = "Create";
	$subject = "Custom Fields";
	if ($id > 0) {
		$cf->load("id = $id");
		$action = "Edit";
		$subject = ucfirst($cf->label);
	}

?>

<form action="<?= $core->get("admin_path"); ?>/custom_fields/save/<?= $id; ?>" target="_blank" method="POST">
	<div class="row content-middle">
		<div class="os-min padr2">
			<h2 class="marg0"><? echo sprintf($core->get("page_title"), $action, $subject); ?></h2>
		</div>
		<div class="os padl2">
			<a href="/admin/custom_fields/edit/0" class="btn">New Fieldset</a>
		</div>
	</div>

	<div class="content pad2 padl0">
		<label for="">Title</label>
		<div class="row margb2">
			<div class="os padr2">
				<input type="text" name="title" value="<?= $cf->label; ?>" placeholder="Title">
			</div>
			<div class="os-2">
				<input type="submit" class="marg0" value="Save">
			</div>
		</div>
		<div class="fieldset_outer row">
			<div class="os-4 label strong pad1">Label</div>
			<div class="os-4 slug strong pad1">Slug</div>
			<div class="os-4 type strong pad1">Type</div>
			<div class="os-12 cf_fields">

			</div>
		</div>
		<div class="fieldset_footer pad1 margt1 bg-light-grey">
			<a href="#" class="btn pull-right" data-template=".blank_field" data-target=".cf_fields" data-cf="create" data-index="1">+ Add Field</a>
			<div class="clear"></div>
		</div>

		<div class="load_rules padt2">
			<h3>Load Conditions</h3>
		</div>
	</div>


	<!-- Basic Row Template -->
	<template class="blank_field">
		<div class="entry">
			<a href="#" data-accordion=".accordion.settings_field_$i" class="row accordion_handle toggled">
				<div data-value="field_$i_label" class="os-4 label pad1">(no label)</div>
				<div data-value="field_$i_slug" class="os-4 slug pad1">(no slug)</div>
				<div data-value="field_$i_type" class="os-4 type pad1">text</div>
			</a>
			<div class="accordion settings_field_$i toggled pad1 border">
				<div class="row content-middle g1 padx1">
					<div class="os-4">
						<!-- <label for="#field_$i_label">Label</label> -->
						<input type="text" class="field_$i_label" name="field[$i][label]" data-bind="field_$i_label" value="" placeholder="Label">
					</div>
					<div class="os-4">
						<!-- <label for="#field_$i_type">Slug</label> -->
						<input type="text" class="field_$i_slug" name="field[$i][slug]" data-bind="field_$i_slug" value="" placeholder="Slug">
					</div>
					<div class="os-4">
						<!-- <label for="#field_$i_type">Type</label> -->
						<select name="field[$i][type]" class="cf_type field_$i_type" data-id="field_$i" data-bind="field_$i_type">
							<optgroup label="Basic">
								<option value="text">Text</option>
								<option value="textarea">Textarea</option>
								<option value="number">Number</option>
								<option value="range">Range</option>
								<option value="link">Link</option>
							</optgroup>
							<optgroup label="Content">
								<option value="wysiwyg">Text Editor</option>
								<option value="file">File Upload</option>
								<option value="image">Image</option>
							</optgroup>
							<optgroup label="Relationship">
								<option value="post">Post</option>
								<option value="user">User</option>
								<option value="form">Form</option>
							</optgroup>
							<optgroup label="Choice">
								<option value="select">Select</option>
								<option value="boolean">True / False</option>
								<option value="checkbox">Checkbox</option>
								<option value="color">Color</option>
								<option value="date">Date</option>
								<option value="radio">Radio</option>
							</optgroup>
							<optgroup label="Layout">
								<option value="group">Group</option>
								<option value="accordion">Accordion</option>
								<option value="flexible">Flexible Content</option>
								<option value="repeater">Repeater</option>
								<option value="tab">Tab</option>
							</optgroup>
						</select>
					</div>
				</div>
				
				<div class="fieldset">
					<div class="row content-middle g1 padx1">
						<div class="os-1">
							<label for="">Required</label>
						</div>
						<div class="os-1">
							<input type="checkbox">
						</div>
						<div class="os-1">
							<label for="">Instructions</label>
						</div>
						<div class="os">
							<textarea name="" style="height: 44px"></textarea>
						</div>
						
					</div>
				</div>

				<div class="field_options field_$i" data-id="field_$i">

				</div>
			</div>
		</div>
	</template>
</form>
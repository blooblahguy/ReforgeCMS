<?
	$id = $core->get("fieldset_id");
	$cf = new CustomField();
	$action = "Create";
	$subject = "Custom Fields";
	if ($id > 0) {
		$cf->load("id = $id");
		$action = "Edit";
		$subject = ucfirst($cf->label);
	}

	// debug($top_key);

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

	<div class="content">
		<label for="">Title</label>
		<div class="row margb2 cfheader">
			<div class="os padr2">
				<input type="text" name="title" value="<?= $cf->label; ?>" placeholder="Title">
			</div>
			<div class="os-2">
				<input type="submit" class="marg0" value="Save">
			</div>
		</div>
		<div class="fieldset_outer row">
			<div class="os label strong pad1">Label</div>
			<div class="os slug strong pad1">Slug</div>
			<div class="os type strong pad1">Type</div>
			<div class="os-2 type strong pad1">Actions</div>
			<div class="os-12 cf_fields">

			</div>
		</div>
		<div class="fieldset_footer pad1 margt1 bg-light-grey">
			<a href="#" class="btn pull-right cf-add" data-target=".cf_fields" data-id="<?= $id; ?>">+ Add Field</a>
			<div class="clear"></div>
		</div>

		<div class="load_rules padt2">
			<h3>Load Conditions</h3>
		</div>

		<div class="load_rules pady2">
			<h3>Display Options</h3>
		</div>
	</div>


	<!-- Basic Row Template -->
	<template class="blank_field">
		<div class="entry entry$key" data-key="$key" data-parent="$parent">
			<a href="#" data-accordion=".accordion.settings_$key" class="row accordion_handle toggled content-middle">
				<div data-value="cfields[$key][label]" class="os label pad1">(no label)</div>
				<div data-value="cfields[$key][slug]" class="os slug pad1">(no slug)</div>
				<div data-value="cfields[$key][type]" class="os type pad1">text</div>
				<div class="os-2 type"><span data-remove=".entry$key" class="remove pad1">Remove</span></div>
			</a>
			<div class="accordion settings_$key toggled pad1 border">
				<div class="row content-middle g1 padx1">
					<div class="os-4">
						<input type="text" class="label" required name="cfields[$key][label]" value="" placeholder="Label" data-bind>
					</div>
					<div class="os-4">
						<input type="text" class="slug" required name="cfields[$key][slug]" value="" placeholder="Slug" data-bind>
					</div>
					<div class="os-4">
						<select name="cfields[$key][type]" class="cf_dropdown type" data-key="$key" data-parent="$parent" data-bind>
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

				<div class="field_options $key" data-id="$key">

				</div>
			</div>
		</div>
	</template>
</form>
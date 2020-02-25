<?
	$id = $core->get("fieldset_id");
	$cf = new CustomField();
	$action = "Create";
	$subject = "Custom Fields";
	$fields = array();
	if ($id > 0) {
		$cf->load("id = $id");
		$action = "Edit";
		$subject = ucfirst($cf->title);
		$fields = unserialize($cf->fieldset);
	}
?>

<div class="row content-middle">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo sprintf($core->get("page_title"), $action, $subject); ?></h2>
	</div>
	<div class="os padl2">
		<a href="/admin/custom_fields/edit/0" class="btn">New Fieldset</a>
	</div>
</div>

<form action="<?= $core->get("admin_path"); ?>/custom_fields/save/<?= $id; ?>" target="_blank" method="POST">
	<label for="title">Title</label>
	<div class="row margb2 cfheader">
		<div class="os padr2">
			<input type="text" name="title" value="<?= $cf->title; ?>" placeholder="Title">
		</div>
		<div class="os-2">
			<input type="submit" class="marg0" value="Save">
		</div>
	</div>

	<? 
	// Renders the fields
	do_action("edit_rcf_fields", $id); ?>
		
	<div class="load_rules padt2">
		<h3>Load Conditions</h3>
	</div>

	<div class="load_rules pady2">
		<h3>Display Options</h3>
	</div>
</form>
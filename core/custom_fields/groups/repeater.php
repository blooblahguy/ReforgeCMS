<?

$settings_template = function($key, $parent_key, $values = array()) { ?>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Sub Fields</label>
			</div>
			<div class="os">
				<div class="fieldset_outer row">
					<div class="os-4 label strong pad1">Label</div>
					<div class="os-4 slug strong pad1">Slug</div>
					<div class="os-4 type strong pad1">Type</div>
				</div>

				<div class="cf_subfields <?= $key; ?>_subfields">
				
				</div>
				
				<div class="bg-light-grey pad1 text-right border">
					<a href="#" class="btn btn-mini cf-add" data-target=".<?= $key; ?>_subfields" data-id="<?= $key; ?>">+ Add Row</a>
				</div>
			</div>
		</div>
	</div>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Minimum Rows</label>
			</div>
			<div class="os">
				<input type="checkbox" name="" value=""<?= $values["minimum"]; ?>">
			</div>
		</div>
	</div>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Maximum Rows</label>
			</div>
			<div class="os">
				<input type="text" name="" value="<?= $values["max"]; ?>">
			</div>
		</div>
	</div>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Button Label</label>
			</div>
			<div class="os">
				<input type="text" name="" value="<?= $values["label"]; ?>">
			</div>
		</div>
	</div>
<? };

$form_template = function($field_id, $parent_id = 0, $values = array()) { ?>

<? };

$repeater = \CFS\Core::instance()->add_element("repeater", array(
	"settings_template" => $settings_template,
	"form_template" => $form_template,
));
?>
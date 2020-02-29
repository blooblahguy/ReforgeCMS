<?

$settings_template = function($field_id, $parent_id = 0, $values = array()) { ?>
	<div class="row g1 content-middle">
		<div class="os-min fieldset">
			<div class="row content-middle g1 padx1">
				<div class="os-min text-center">
					<label for="">Default Value</label>
				</div>
				<div class="os">
					<input type="checkbox" name="" value="1">
				</div>
			</div>
		</div>
		<div class="os fieldset">
			<div class="row content-middle g1 padx1">
				<div class="os-1 text-center">
					<label for="">Label</label>
				</div>
				<div class="os">
					<input type="text" name="" value="<?= $values["label"]; ?>">
				</div>
			</div>
		</div>
	</div>
<? };

$form_template = function($field_id, $parent_id = 0, $values = array()) { ?>

<? };

// $boolean = \CFS\Core::instance()->add_element("boolean", array(
// 	"settings_template" => $settings_template,
// 	"form_template" => $form_template,
// ));
?>
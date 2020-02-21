<?

$settings_template = function($field_id, $parent_id = 0, $values = array()) { ?>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Default Value</label>
			</div>
			<div class="os">
				<input type="text">
			</div>
		</div>
	</div>
	<div class="fieldset">
		<div class="row content-middle g1 padx1">
			<div class="os-1">
				<label for="">Placeholder</label>
			</div>
			<div class="os">
				<input type="text">
			</div>
		</div>
	</div>
<? };

$form_template = function($field_id, $parent_id = 0, $values = array()) { ?>

<? };

$text = \CFS\Core::instance()->add_element("text", array(
	"settings_template" => $settings_template,
	"form_template" => $form_template,
));
?>
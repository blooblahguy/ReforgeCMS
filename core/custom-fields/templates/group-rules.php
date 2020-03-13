<?

if (count($all_rules) == 0) {
	$all_rules[] = array();
}

?>
	<div class="load_rules padt2">
	<div class="fieldset load_conditions">
	<div class="row g1 padx1">
		<div class="os-2">
			<label for="">Load Conditions</label>
			<p class="description em">Show these fields if the following conditions are met</p>
		</div>
		<div class="os">
			<div class="rcf_rules">
				<? foreach( $all_rules as $group => $rules ) { 
					rcf_get_template('group-rule', array( 'rules' => $rules, 'group' => $group ));
				} ?>
			</div>
			<a href="#" class="btn bt-mini rcf-add-rulegroup" data-target=".rcf_rules" data-index="<?= count($rules); ?>">Or</a>
		</div>
	</div>
</div>

<?
	$clone = array(
		"key" => false,
		"expression" => false,
		"value" => false
	)
?>
<template class="blank_rule">
	<? rcf_get_template('group-rule', array("rules" => array($clone), "group" => "\$group")); ?>
</template>
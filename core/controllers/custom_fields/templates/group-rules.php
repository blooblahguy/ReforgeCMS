<?php

if (count($all_rules) == 0) {
	$all_rules[] = array(array());
}

?>
<p class="description">Show these fields if the following conditions are met</p>
<div class="load_rules">
	<div class="fieldset load_conditions">
	
		<div class="rcf_rules">
			<? foreach( $all_rules as $group => $rules ) { 
				rcf_get_template('group-rule', array( 'rules' => $rules, 'group' => $group ));
			} ?>
		</div>
		<a href="#" class="btn bt-mini rcf-add-rulegroup" data-target=".rcf_rules" data-index="<?= count($rules); ?>">Or</a>

	</div>
</div>

<?
	$clone = array(
		"key" => false,
		"expression" => false,
		"value" => false
	)
?>
<template class="template blank_rule">
	<? rcf_get_template('group-rule', array("rules" => array($clone), "group" => "\$group")); ?>
</template>
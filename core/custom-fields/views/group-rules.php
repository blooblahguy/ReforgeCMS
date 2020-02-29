<?

if (count($rules) == 0) {
	$rules[] = array();
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
			<? foreach( $rules as $i => $rule ) { 
				rcf_get_view('group-rule', array( 'rule' => $rule, 'group' => $i ));
			} ?>
			</div>
			<a href="#" class="btn bt-mini rcf-add-rulegroup" data-target=".rcf_rules" data-index="<?= count($rules); ?>">Or</a>
		</div>
	</div>
</div>

<template class="blank_rule">
	<? rcf_get_view('group-rule', array("group" => "\$group")); ?>
</template>
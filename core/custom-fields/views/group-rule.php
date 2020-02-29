<?

$all_rules = RCF::instance()->rules;
$current_rule = false;

?>

<div class="rule_group padx2 margb2 rule_group_<?= $group; ?>">

	<div class="row g1 condition_row content-middle">
		<div class="os">
			<select name="load_conditions[<?= $group; ?>][key][]" class="load_key">
				<? foreach ($all_rules as $key => $value) { ?>
					<option value="<?= $key; ?>" <? if ($key == $current_rule) ?>><?= $value->label; ?></option>
				<? } ?>
			</select>
		</div>
		<div class="os-2">
			<select name="load_conditions[<?= $group; ?>][expression][]" class="load_expression">
				<option value="==">Is Equal To</option>
				<option value="!=">Is Not Equal To</option>
			</select>
		</div>
		<div class="os">
			<select name="load_conditions[<?= $group; ?>][value][]" class="rule_values">

			</select>
		</div>

		<div class="os-min">
			<a href="#" class="btn btn-sm rcf-add-rule" data-target=".rule_group_<?= $group; ?>">And</a>
		</div>

		<div class="os-min">
			<a href="#" class="btn btn-sm rcf-remove-rule">X</a>
		</div>
	</div>
			

</div>


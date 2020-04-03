<?

$all_rules = RCF::instance()->rules;

$selceted_rule = false;
$first_rule = false;

if ($group !== 0) {
	echo '<div class="rule_group_outer">';
		echo '<label for="">Or</label>';
}

?>
<div class="rule_group padx2 margb2 rule_group_<?= $group; ?>">
<?

	foreach ($rules as $i => $rule_values) {
		$current_rule = $rule_values["key"];
		$current_exp = $rule_values["expression"];
		$current_value = $rule_values["value"];	
		?>

		<div class="row g1 condition_row content-middle">
			<div class="os">
				<select name="load_conditions[<?= $group; ?>][key][]" class="load_key">
					<? foreach ($all_rules as $key => $value) {
						if (! $first_rule) { $first_rule = $key; } ?>
						<option value="<?= $key; ?>" <? if ($key == $current_rule) { echo "selected"; $selceted_rule = $key; } ?>><?= $value->label; ?></option>
					<? } ?>
				</select>
			</div>
			<div class="os-2">
				<select name="load_conditions[<?= $group; ?>][expression][]" class="load_expression">
					<option value="==" <?if ($current_exp == "==") {echo "selected"; } ?>>Is Equal To</option>
					<option value="!=" <?if ($current_exp == "!=") {echo "selected"; } ?>>Is Not Equal To</option>
				</select>
			</div>
			<div class="os">
				<? if (! $selceted_rule) {$selceted_rule = $first_rule; } ?>
				<select name="load_conditions[<?= $group; ?>][value][]" class="rule_values loaded">
					<? RCF::instance()->get_rule_type_choices($selceted_rule, $current_value); ?>
				</select>
			</div>

			<div class="os-min">
				<a href="#" class="btn rcf-add-rule" data-target=".rule_group_<?= $group; ?>">And</a>
			</div>

			<div class="os-min rcf-delete-rule">
				<a href="#" class="btn btn-sm rcf-remove-rule">X</a>
			</div>
		</div>

	<?
	}
	?>

</div>

<?

if ($group != 0) {
	echo '</div>';
}


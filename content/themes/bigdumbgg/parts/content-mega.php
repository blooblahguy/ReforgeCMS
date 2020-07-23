<?
	$fields = get_fields();
	if (! $fields['section']) { return; }

	foreach ($fields['section'] as $row) {
		$classes = array();
		if ($row['background']) { $classes[] = $row['background']; }
		
		?>
		<div class="container section <?= implode(" ", $classes); ?>">
			<? foreach ($row['content'] as $content) {
				$layout = row_layout($content);
				?>
				<div class="field-<?= $layout; ?>">
					<?
					include(theme_dir()."/parts/fields/$layout.php");
					?>
				</div>
			<? } ?>
		</div>
	<? }
?>
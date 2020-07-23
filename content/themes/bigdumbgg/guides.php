<? get_template_part("parts", "guides-header"); ?>

<div class="container">
	<div class="row g1">
		<div class="os padt2">
			<?

						
			$fields = get_fields();
			$links = $fields['links'];
			$sections = $fields['sections'];

			?>

			<? foreach ($sections as $row) { ?>
				<div class="guide_section margb3">
					<h2><?= $row['title']; ?></h2>
					<? 
					$content = $row['content'];
					foreach ($content as $field) { ?>
						<div class="guide_content">
						<?
							echo $field['text'];
						?>
						</div>
					<? } ?>
					
				</div>

			<? } ?>
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>
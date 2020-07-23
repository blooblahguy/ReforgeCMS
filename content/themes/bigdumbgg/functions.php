<?

	// theme functions
	add_permission(array(
		"slug" => "view_guides",
		"label" => "View Raid Guides",
		"description" => "Allow users to view all guides"
	));

	function row_layout($row) {
		return reset(array_keys($row));
	}
	
	function render_socials() {
		$socials = get_field("socials", "settings"); 

		// debug($socials);

		?>
		<div class="bdg">
			<? foreach ($socials as $platform => $link) { 
				$plat = strrev($platform); ?>
				<a href="<?= $link; ?>" target="_blank" class="<?= $plat; ?>">
					<span class="svg">
						<? echo get_file_contents_url("/core/assets/img/".$platform.".svg"); ?>
					</span>
				</a>
			<? } ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<?
	}

?>
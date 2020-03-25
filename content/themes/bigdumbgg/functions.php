<?

	// theme functions

	function render_socials() {
		$socials = get_field("socials", "settings"); 

		?>
		<div class="socials">
			<? foreach ($socials as $platform => $link) { ?>
				<a href="<?= $link; ?>" target="_blank" class="<?= $platform; ?>">
					<span class="svg">
						<? echo get_file_contents_url("/core/img/".$platform.".svg"); ?>
					</span>
				</a>
			<? } ?>
		</div>
		<?
	}

?>
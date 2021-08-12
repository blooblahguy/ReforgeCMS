				</div>
			</div>
		</div>
		
		<div class="footer_outer">
			<div class="footer container pady2">
				<div class="text-center footer_socials">
					<? render_socials(); ?>
				</div>
				<div class="row g2 content-middle">
					<a href="/" class="os-md-4 padx4 os-12 text-center md-text-left footerlogo fill-primary text-center">
						<span class="svglogo"><?= get_file_contents_url(theme_url()."/img/bdgg.svg"); ?></span>
						<h2 class="margb0">BD<span>GG</span></h2>
					</a>
					<div class="os footer_menu">
						<div class="row g1">
							<? 
							$menu = get_menu('footer-menu');
							foreach ($menu as $header) { ?>
								<div class="os-6 text-center os-md md-text-left menu_sec">
									<div class="header">
										<?= $header['html']; ?>
									</div>
									<? foreach ($header['children'] as $link) { ?>
										<div class="child">
											<?= $link['html']; ?>
										</div>
									<? } ?>
								</div>
							<? } ?>
						</div>
					</div>
				</div>
				<div class="adspace footer"></div>
				<?
				//global $db;
				//debug($db);
				?>
				<div class="os-12 muted copy em small text-center"> © <?= Date("Y"); ?> Big Dumb Gaming LLC - All rights reserved</div>
			</div>

			
		</div>
	</div>

	<? rf_footer(); ?>
	<script src="/content/themes/bigdumbgg/js/cash.js"></script>
	<script type="text/javascript" src="/content/themes/bigdumbgg/js/scripts.js"></script>

	<script>const whTooltips = {colorLinks: true, iconizeLinks: true, renameLinks: true};</script>
	<script src="https://wow.zamimg.com/widgets/power.js"></script>

</body>
</html>
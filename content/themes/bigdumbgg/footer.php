				</div>
			</div>
		</div>

		<div class="footer_outer bg-dark">
			<div class="footer container pady2">
				<div class="row g2 content-middle">
					<a href="/" class="os-md-min padx4 os-12 text-center md-text-left footerlogo fill-primary text-center">
						<span class="svglogo"><?= get_file_contents_url(theme_url()."/img/bdgg.svg"); ?></span>
						<h2 class="margb0">BD<span>GG</span></h2>
					</a>
					<div class="os footer_menu">
						<div class="row g1">
							<? 
							$menu = get_menu('footer-menu');
							foreach ($menu as $header) { ?>
								<div class="os-6 text-center os-md os-md-text-left menu_sec">
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
			</div>
		</div>
		<div class="subfooter">
			<div class="container row pady1 content-middle">
				<div class="os-12 text-center md-text-left os-md-6">
					<? render_socials(); ?>
				</div>
				<div class="os-12 md-text-right os-md-6 muted copy em small text-center"> © <?= Date("Y"); ?> Big Dumb Gaming LLC - All rights reserved</div>
			</div>
		</div>
	</div>

	<? rf_footer(); ?>
</body>
</html>
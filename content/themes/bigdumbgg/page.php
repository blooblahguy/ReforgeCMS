<? get_template_part("parts", "page-header"); ?>

<div class="container">
	<div class="row g1">
		<div class="os pady0">
			<?
			get_template_part("parts", "content-mega");
			the_content();
			?>		
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>
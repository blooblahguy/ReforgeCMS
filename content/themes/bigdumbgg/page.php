<? get_template_part("parts", "page-header"); ?>

<div class="container">
	<div class="row g1">
		<div class="os pady0">
			<?
			
			// do_action("page/{$page['id']}/content_high", $page);
			get_template_part("parts", "content-mega");
			// do_action("page/{$page['id']}/content", $page);
			// do_action("page/{$page['id']}/content_low", $page);
			?>		
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>
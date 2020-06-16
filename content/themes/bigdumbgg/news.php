<? get_template_part("parts", "page-header"); ?>


<div class="container">
	<div class="row g1">
		<div class="os pady0">
			<div class="section dark pad0">
				<div class="post_info row g1 content-middle">
					<div class="os"></div>
					<div class="os-min">
						<?= the_date(); ?>
					</div>
					<div class="os-min">
						<?= the_author(); ?>
					</div>
					<div class="os"></div>
				</div>
			</div>

			<div class="post_hero padt2">
				<img src="<?= get_file(get_field("featured_image"))['original']; ?>" alt="<?= $page->title; ?>">
			</div>
			<div class="post_body pady2">
				<?= $page->content; ?>
			</div>
			<?

			do_action("page/{$page['id']}/content_high", $page);
			get_template_part("parts", "content-mega");
			do_action("page/{$page['id']}/content", $page);
			do_action("page/{$page['id']}/content_low", $page);
			?>		
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>
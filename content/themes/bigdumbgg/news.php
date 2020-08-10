<? get_template_part("parts", "news-header"); ?>


<div class="container">
	<div class="row">
		<div class="os"></div>
		<div class="os-12 os-lg-8 os-md-10 news_content">
			<div class="post_hero padt2 text-center">
				<img src="<?= get_file(get_field("featured_image"))['original']; ?>" alt="<?= $page->title; ?>">
			</div>
			<div class="post_body pady2">
				<?= $page->content; ?>
			</div>
			<?
			get_template_part("parts", "content-mega");
			?>

			<div class="text-center pady4">
				<a href="#" class="btn">Back to Top</a>
			</div>
		</div>
		<div class="os"></div>
	</div>

	
</div>
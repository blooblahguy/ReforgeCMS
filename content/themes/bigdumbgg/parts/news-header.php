<?php
$breadcrumbs = $page->get_breadcrumbs();

// $bg = get_file(get_field("featured_image"));
$sub = $page['subtitle'];
// if ($bg) {
// 	$bg = $bg['original'];
// } else {
// 	// $hero = rand(1, 8);
// 	// $bg = "/content/uploads/banner_{$hero}.raw.png";
// }

?>
<div class="page_header_outer <?= $page->post_type; ?>">
	<? if ($bg) { ?>

		<img src="<?= $bg; ?>" alt="<?= $page['title']; ?>" class="bg">
	<? } else { ?>
		<div class="background-svg1 bg"></div>
	<? } ?>
	<div class="page_header container">
		<h1><?= $page['title']; ?></h1>
		<div class="clear"></div>
		<? if ($sub) { ?>
			<h3><?= $sub; ?></h3>
		<? } ?>
		<div class="date small">
			<? the_date(); ?>
		</div>
		<div class="breadcrumbs_outer">
			<?
			$breadcrumbs = $page->get_breadcrumbs();
			if ($breadcrumbs) {
				render_breadcrumbs($breadcrumbs);
			}
			?>
		</div>
		
	</div>
	
</div>
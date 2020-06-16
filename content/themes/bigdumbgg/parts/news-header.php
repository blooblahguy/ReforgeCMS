<?
$breadcrumbs = $page->get_breadcrumbs();


$sub = $page['subtitle'];

?>
<div class="news_header_outer <?= $page->post_type; ?>">
	<? if ($bg) { ?>

		<img src="<?= $bg; ?>" alt="<?= $page['title']; ?>" class="bg">
	<? } else { ?>
		<div class="background-svg1 bg"></div>
	<? } ?>
	<div class="news_header container">
		<h1><?= $page['title']; ?></h1>
		<div class="clear"></div>
		<? if ($sub) { ?>
			<h3><?= $sub; ?></h3>
		<? } else {
			$breadcrumbs = $page->get_breadcrumbs();
			if ($breadcrumbs) {
				render_breadcrumbs($breadcrumbs);
			}
		} ?>
	</div>
</div>
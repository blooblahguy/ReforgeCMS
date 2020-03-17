<?
$bg = $fields;
$sub = $page->subtitle;
if (! $bg) {
	// $bg = rand;
	$hero = rand(1, 8);
	$bg = "/content/uploads/banner_{$hero}.raw.png";
}

?>
<div class="page_header_outer">
	<img src="<?= $bg; ?>" alt="<?= $page->title; ?>" class="bg">
	<div class="page_header container">
		<h1><?= $page->title; ?></h1>
		<? if ($sub) { ?>
			<h3><?= $sub; ?></h3>
		<? } ?>
	</div>
</div>
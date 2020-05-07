<?
$image = $content['side_by_side']['image'];
$image = get_file($image);
$text = $content['side_by_side']['text'];
$alignment = $content['side_by_side']['alignment'];

?>
<div class="row g2 content-middle">
	<? if ($alignment == "left") { ?>
		<div class="os-4 img text-center">
			<img class="float" src="<?= $image['original']; ?>">
		</div>		
	<? } ?>
	<div class="os"><?= $text; ?></div>
	<? if ($alignment == "right") { ?>
		<div class="os-5 img">
			<img class="float" src="<?= $image['original']; ?>">
		</div>
	<? } ?>
</div>
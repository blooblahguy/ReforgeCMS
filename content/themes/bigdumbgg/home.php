<!-- <div class="home_parallax" id="scene">
	<img data-depth="0.1" src="/content/themes/bigdumbgg/img/parallax.jpg" alt="" class="bg">
	<div class="container">
		<img data-depth="0.2" src="/content/themes/bigdumbgg/img/troll.png" alt="" class="troll">
		<img data-depth="0.4" src="/content/themes/bigdumbgg/img/orc.png" alt="" class="orc">
		<img data-depth="0.6" src="/content/themes/bigdumbgg/img/belf.png" alt="" class="belf">
		<img data-depth="0.8" src="/content/themes/bigdumbgg/img/tauren.png" alt="" class="tauren">
	</div>
</div> -->

<div class="container pady2">
	Homepage
</div>

<? 
$fields = get_fields();
$bg = $fields['hero_text']['recruitment_hero'];
$bg = get_file($bg); 
?>
<div class="home_hero text-center">
	<img src="<?= $bg['original']; ?>" alt="Big Dumb Gaming Homepage Hero">
	<div class="text_overlay">
		<div class="container row text-left">
			<div class="os-lg-6 os">
				<h2><?= $fields['hero_text']['main']; ?></h2>
				<h3 class="margb4"><?= $fields['hero_text']['sub_text']; ?></h3>
				<a class="btn-primary" href="/apply">I Can Handle That</a>
			</div>
		</div>
	</div>

</div>

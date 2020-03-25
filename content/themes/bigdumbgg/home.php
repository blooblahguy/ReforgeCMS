<div class="container pady2">
	Homepage
</div>

<? 
$bg = get_file(get_field("page_hero")); 
$fields = get_fields();
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

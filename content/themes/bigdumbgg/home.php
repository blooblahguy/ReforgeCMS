	<?
	$posts = Content::instance()->query("news", array(
		"order by" => "created DESC",
		"limit" => "4",
	));

	// debug($posts);
	$latest = array_shift($posts);

	$latest = new Post($latest['id']);
	$latest->load_meta();

	// debug($latest->meta);

	if ($latest->meta['featured_image']) {
		$latest_featured = new File();
		$latest_featured->load("*", array("id = :id", ":id" => $latest->meta['featured_image']));
		$latest_featured = $latest_featured->get_size(1200);
	}

	?>

<div class="featured_news bg-dark">

	<a href="<?= $latest->get_permalink(); ?>" class="feat">
		<img src="<?= $latest_featured; ?>" alt="" class="bg">
		<div class="container">
			<div class="row g1 content-middle content-justify">
				<div class="os-md-8">
					<h1><?= $latest['title']; ?></h1>
					<h3><?= $latest['subtitle']; ?></h3>
					<br>
					<br>
					<div class="btn-primary">Read More &raquo;</div>
				</div>
			</div>
			
			
		</div>
	</a>
</div>
<div class="partners bg-dark">
	<div class="container">
		<div class="row g2 content-middle content-justify">
			<a href="/partners" class="os home_partner gg"><img src="/content/themes/bigdumbgg/img/goldenguardians.png" alt=""></a>
			<a href="/partners" class="os home_partner tm"><img src="/content/themes/bigdumbgg/img/ticketmaster.png" alt=""></a>
			<a href="/partners" class="os home_partner zenni"><img src="/content/themes/bigdumbgg/img/zenni.png" alt=""></a>
		</div>
	</div>
</div>
<div class="container padb2 margt2">
	<!-- Streams -->
	<?

	?>
	<div class="row g1">
		<div class="os padt0">
			<div class="row g1">

				<? foreach ($posts as $news_array) {
					$news = new Post($news_array['id']);
					$news->load_meta();

					$featured = new File();
					if ($news->meta['featured_image']) {
						$featured->load("*", array("id = :id", ":id" => $news->meta['featured_image']));
						$featured = $featured->get_size(600);
					}

					?>
					<div class="os-lg-12 os-6">
						<a href="<?= $news->get_permalink(); ?>" class="card news_card">
							<img src="<?= $featured; ?>" alt="" class="bg">
							<h1 class="marg0"><?= $news['title']; ?></h1>
							<p class="marg0"><?= $news['subtitle']; ?></p>
						</a>
					</div>
				<? } ?>

			</div>
		</div>
		<div class="os-3 sidebar">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
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
				<h1><?= $fields['hero_text']['main']; ?></h1>
				<h3 class="margb4"><?= $fields['hero_text']['sub_text']; ?></h3>
				<a class="btn-primary" href="/recruitment">I Can Handle That</a>
			</div>
		</div>
	</div>

</div>

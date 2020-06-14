	<?
	$posts = Content::instance()->query("news");

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

<div class="page_header bg-dark">

	<a href="<?= $latest->get_permalink(); ?>" class="card latest news_card">
		<img src="<?= $latest_featured; ?>" alt="" class="bg">
		<div class="container">
			<div class="row g1 content-middle content-justify">
				<div class="os-md-8">
					<h1><?= $latest['title']; ?></h1>
					<h3><?= $latest['subtitle']; ?></h3>
				</div>
				<div class="os-min">
					<div class="btn-primary">Read More &raquo;</div>
				</div>
			</div>
			
			
		</div>
	</a>
</div>
<div class="partners bg-dark pad2 padb0">
	<div class="container">

		<div class="row g2 content-middle content-justify">
			<div class="os home_partner"><img src="/content/themes/bigdumbgg/img/goldenguardians.svg" alt=""></div>
			<div class="os home_partner"><img src="/content/themes/bigdumbgg/img/ticketmaster.png" alt=""></div>
			<div class="os home_partner"><img src="/content/themes/bigdumbgg/img/zenni-logo.webp" alt=""></div>
		</div>
	</div>
</div>
<div class="container padb2 margt2">
	<!-- Streams -->
	<?

	

// 
	// require_once("lib/bdg_twitch.php");
	// $api = new \BDG\Twitch("so3d8hae0pmk8nz6kuwel6fhdbpofw3");

	// $team = $api->checkTeam("bdg");

	?>
	<h2 class="text-center">More News</h2>
	<div class="row g1">
		<div class="os">
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
				<div class="os-6">
					<div class="card news_card">
						<img src="<?= $featured; ?>" alt="" class="bg">
						<h2><?= $news['title']; ?></h2>
						<p><?= $news['subtitle']; ?></p>
					</div>
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
				<h2><?= $fields['hero_text']['main']; ?></h2>
				<h3 class="margb4"><?= $fields['hero_text']['sub_text']; ?></h3>
				<a class="btn-primary" href="/apply">I Can Handle That</a>
			</div>
		</div>
	</div>

</div>

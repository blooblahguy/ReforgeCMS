<?

$posts = Content::instance()->query("news", array(
	"order by" => "created DESC",
	"limit" => "5",
));

// debug($posts);
// $latest = array_shift($posts);

// $latest = new Post($latest['id']);
// $latest->load_meta();

// // debug($latest->meta);

// if ($latest->meta['featured_image']) {
// 	$latest_featured = new File();
// 	$latest_featured->load("*", array("id = :id", ":id" => $latest->meta['featured_image']));
// 	$latest_featured = $latest_featured->get_size(1200);
// }

?>

<div class="home_feature relative">
	<img src="/content/themes/bigdumbgg/img/home_hero.jpg" alt="" class="full-stretch">
	<div class="container text-center">
		<div class="row">
			<div class="os"></div>
			<div class="os-min content pad2">
				<h1>US #2 World of Warcraft Raiding Guild</h1>
				<h3>BDGG is a unique raid team with a focus on content creation, quality guides, and giving back to the community</h3>
				
				<div class="partners text-center padt2">
					<h3 class="margb0"><a href="/partners">Our Partners</a></h3>
					<div class="container">
						<div class="row g2 content-middle content-center">
							<a href="/partners" class="os-hidden os-12 os-lg-min os-md-display-block home_partner gg"><img src="/content/themes/bigdumbgg/img/goldenguardians.png" alt=""></a>
							<a href="/partners" class="os-hidden os-12 os-lg-min os-md-display-block home_partner tm"><img src="/content/themes/bigdumbgg/img/ticketmaster.png" alt=""></a>
							<a href="/partners" class="os-hidden os-12 os-lg-min os-md-display-block home_partner zenni"><img src="/content/themes/bigdumbgg/img/zenni.png" alt=""></a>
						</div>
					</div>
				</div>
			</div>
			<div class="os"></div>
			<? if (current_user()->can("view_applications")) { ?>
				<div class="os application_updates text-left">
					<? get_partial("applications"); ?>
				</div>
			<? } ?>
		</div>
	</div>
</div>


<div class="container padb2 home_next">
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
			<div class="os-12 os-md-6 os-lg-4 news_card">
				<div class="inner">
					<a href="<?= $news->get_permalink(); ?>" class="display-block news_image">
						<img src="<?= $featured; ?>" alt="" class="bg display-block marg0">
					</a>
					<div class="news_text">
						<a href="<?= $news->get_permalink(); ?>"><h2><?= $news['title']; ?></h2></a>
						<a href="<?= $news->get_permalink(); ?>"><h3><?= $news['subtitle']; ?></h3></a>
					</div>
				</div>
			</div>
		<? } ?>
		<div class="os-12 os-md-6 os-lg-4">
			<div class="inner pad1 bg-dark">
				<? get_partial("twitter-feed"); ?>
			</div>
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
				<h2 class="title"><?= $fields['hero_text']['main']; ?></h2>
				<h3 class="margb4"><?= $fields['hero_text']['sub_text']; ?></h3>
				<a class="btn-secondary" href="/recruitment">I Can Handle That</a>
			</div>
		</div>
	</div>
</div>

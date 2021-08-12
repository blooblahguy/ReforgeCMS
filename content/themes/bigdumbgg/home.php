<?php

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

$streams = get_option("live_streams", true);
$feature = array_shift($streams);
$all = $streams;
array_unshift($all, $feature);

?>

<div class="home_feature relative">
	<img src="/content/themes/bigdumbgg/img/home_hero.jpg" alt="" class="full-stretch">
	<? if (isset($feature)) { ?>
		<div class="container">
			<div class="streams">
				<div class="feature">
					<div class="row">
						<div class="os">
							<div id="twitch-embed"></div>
						</div>
						<div class="os-min hidden md-display-block" id="twitch-chat-embed">
							<iframe src="https://www.twitch.tv/embed/<? echo $feature['user_login']; ?>/chat?parent=bigdumb.gg&parent=localhost&darkpopout" width="200"></iframe>
						</div>
					</div>
				</div>
				
				
				<script src="https://embed.twitch.tv/embed/v1.js"></script>
				<script type="text/javascript">
					var embed = new Twitch.Embed("twitch-embed", {
						height: 460,
						channel: "<? echo $feature['user_login']; ?>",
						layout: "video",
						autoplay: false,
						muted: true,
						parent: "bigdumb.gg"
					});

					embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
						var player = embed.getPlayer();
						player.play();
					});
				</script>
				
				<div class="stream_nav row g1">
					<? foreach ($all as $k => $stream) { 
						$width = "300";
						$height = "200";
						$thumb = str_replace("{width}", $width, $stream['thumbnail_url']);
						$thumb = str_replace("{height}", $height, $thumb);

						?>
						<div class="os-12 os-md-4 os-lg-3">
							<a href="#<? echo $stream['user_login']; ?>" id="<? echo $stream['user_login']; ?>" class="streamer_tab<? if ($k == 0) { echo " active";} ?>">
								<div class="row g1 content-middle">
									<div class="os">
										<strong class="title"><? echo $stream['user_name']; ?></strong>
									</div>
									<div class="os-min">
										<span class="live_icon"><xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 122.88" xml:space="preserve"><g><path fill-rule="evenodd" clip-rule="evenodd" d="M61.438,0c33.93,0,61.441,27.512,61.441,61.441 c0,33.929-27.512,61.438-61.441,61.438C27.512,122.88,0,95.37,0,61.441C0,27.512,27.512,0,61.438,0L61.438,0z"/></g></svg></span>
										<span><? echo $stream['viewer_count']; ?></span>
									</div>
								</div>
								<img src="<? echo $thumb; ?>" alt="">
							</a>
						</div>
					<? } ?>
				</div>
			</div>
		</div>
	<? } ?>
	

	<div class="container text-center padt2">
		<div class="row content-middle">
			<div class="os"></div>
			<div class="os-min content pad2">
				<h1>World 4th World of Warcraft Raiding Guild</h1>
				<h3>BDGG is a unique raid team with a focus on content creation, quality guides, and giving back to the community</h3>

				<div class="text-center home_socials padt1">
					<? render_socials(); ?>
				</div>
				
				<div class="partners text-center padt2">
					<h3 class="margb0"><a href="/partners">Our Partners</a></h3>
					<div class="container">
						<div class="row g2 content-middle content-center">
							<a href="/partners" class="os-hidden os-12 os-lg-3 os-md-display-block home_partner gg"><img src="/content/themes/bigdumbgg/img/goldenguardians.png" alt=""></a>
							<a href="/partners" class="os-hidden os-12 os-lg-3 os-md-display-block home_partner tm"><img src="/content/themes/bigdumbgg/img/ticketmaster.png" alt=""></a>
							<a href="/partners" class="os-hidden os-12 os-lg-3 os-md-display-block home_partner zenni"><img src="/content/themes/bigdumbgg/img/zenni.png" alt=""></a>
							<a href="/partners" class="os-hidden os-12 os-lg-3 os-md-display-block home_partner wowhead"><img src="/content/uploads/sizes/wowhead.medium.png" alt=""></a>
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
				<p class="margb4"><?= $fields['hero_text']['sub_text']; ?></p>
				<a class="btn-primary" href="/recruitment">I Can Handle That</a>
			</div>
		</div>
	</div>
</div>

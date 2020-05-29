<?php

//==================================================================
// DISPLAY
//==================================================================
$tweets = get_option('bdg_tweets');
$user = $tweets['user'];
$tweets = $tweets['tweets'];
// $tuser = $tweets['array'][0]['user'];
// $tweets = $tweets['array'];

// echo $tweets['tweets'];
// debug ($tweets);
// debug ($user);

?>
<div class="widget twitter">
	<a href="<?= $user['url']; ?>" target="_blank" class="title rettiwt">
		<div class="row content-middle">
			<div class="os-min padr1 svg fill-white">
				<? echo get_file_contents_url("/core/assets/img/twitter.svg"); ?>
			</div>
			<div class="os">
				@<?= $user['screen_name']; ?>
			</div>
		</div>
	</a>
	<div class="content">
		<? foreach ($tweets as $t) { ?>
			<div class="tweet">
				<a href="<?= $t['link']; ?>" target="_blank" class="title"><?= $user['screen_name']; ?> &bull; <?= Date("Y-m-d", strtotime($t['posted_at'])); ?></a>
				<div class="message"><?= $t['message']; ?></div>
			</div>
		<? } ?>
	</div>
</div>
<?
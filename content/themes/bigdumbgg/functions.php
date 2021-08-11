<?php


	function tweet_template($tweet) {
		$date = strtotime($tweet['created_at']);
		if (date("Y", $date) == date("Y")) {
			$date = date("M j", $date);
		} else {
			$date = date("M j Y");
		}

		$tweet['text'] = json_decode($tweet['text']);

		?>
		<div class="tweet os">
			
			<div class="head row content-middle relative">
				<a href="<?= $tweet['url']; ?>" target="_blank" class="link_overlay"></a>
				<span class="image">
					<img src="<?= $tweet['profile_image_url']; ?>" alt="<?= $tweet['user_nickname']; ?>">
				</span>
				<span class="name"><strong><?= $tweet['user_nickname']; ?></strong></span>
				<span class="user">
					@<a href="<?= $tweet['user_url']; ?>" target="_blank"><?= $tweet['user_name']; ?></a>
				</span>
				<span class="date">
					· <?= $date; ?>
				</span>
			</div>
			<div class="message">
				<div class="text relative">
					<a href="<?= $tweet['url']; ?>" target="_blank" class="link_overlay"></a>
					<?= $tweet['text']; ?>
				</div>
				<? if (isset($tweet['quoted'])) { 
					tweet_template($tweet['quoted']);
				} ?>
			</div>
		</div>
		<?
	}

	// theme functions
	add_permission(array(
		"slug" => "view_guides",
		"label" => "View Raid Guides",
		"description" => "Allow users to view all guides"
	));

	function row_layout($row) {
		return reset(array_keys($row));
	}
	
	function render_socials() {
		$socials = get_field("socials", "settings"); 

		// debug($socials);

		?>
		<div class="bdg">
			<? foreach ($socials as $platform => $link) {
				if ($link == "") { continue; }
				$plat = strrev($platform); ?>
				<a href="<?= $link; ?>" target="_blank" class="<?= $plat; ?>">
					<span class="svg">
						<? echo get_file_contents_url("/core/assets/img/".$platform.".svg"); ?>
					</span>
				</a>
			<? } ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<?
	}

?>
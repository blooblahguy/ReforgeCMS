<?
	$comments = new Comment();
	$comment = $comments->find("*", "post_id = {$app['id']}", array(
		"order by" => "created DESC",
		"limit" => 1
	));
	

	if (! $comment) {
		$comment = array(
			"id" => "",
			"author" => $app['author'],
			"created" => $app['created'],
		);
	} else {
		$comment = reset($comment);
	}
	
	$link = $this->rfa->app_link($app['id']);


?>

<div class="row g1 content-middle">
	<div class="os-min unread_status">
		<div class="fill-primary">
			<?= get_file_contents_url(theme_url()."/img/emblem.svg"); ?>
		</div>
	</div>
	<div class="os">
		<a href="<?= $link; ?>" class="display-block"><?= $app['title']; ?></a>
		<span class="small">Author: <?= get_user($app['author'])->username; ?></span>
	</div>
	<div class="os-min">
		<div class="row content-middle">
			<div class="os-min padx1">
				<div class="avatar_icon">
					<img src="<?= get_user($comment['author'])->avatar; ?>" alt="">
				</div>
			</div>
			<div class="os">
				<a href="<?= $link; ?>#<? $comment['id']; ?>" class="display-block date"><?= smart_date($comment['created']); ?></a>
				<span class="small"><?= get_user($comment['author'])->username; ?></span>
			</div>
		</div>
	</div>
</div>
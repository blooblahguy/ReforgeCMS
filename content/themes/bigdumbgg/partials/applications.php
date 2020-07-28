<?php
if (! $user->can("view_applications")) { return; }

$open = new Post();
$open = $open->find("*", "post_type = 'application' AND post_status = 'open'", array(
	"order by" => "created DESC"
));

if (count($open) == 0) {
	return;
}

?>

<div class="bg-dark pad2 applications_feed card_glow">
	<h3 class="title"><a href="/recruitment/applications" class="text-primary">Applications</a> Activity</h3>
	<div class="content">
		<? foreach ($open as $app) {
			$author = get_user($app['author']);

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

			$link = RFApps()->app_link($app['id']);

			?>
			<div class="app">
				<div class="row content-middle">
					<div class="os-min padr1">
						<div class="avatar_icon">
							<img src="<?= $author->avatar; ?>" alt="">
						</div>
					</div>
					<div class="os">
						<div class="post_title">
							<a href="<?= $link; ?>#comment<?= $comment['id']; ?>"><?= $app['title']; ?></a>
						</div>
						<div class="post_info row content-middle">
							<div class="os">
								<span class="user"><span class="small <?= $author['class']; ?>"><?= $author['username']; ?></strong></span>
							</div>
							<div class="os small text-right">
								<a href="<?= $link; ?>#comment<?= $comment['id']; ?>" class="display-block date"><?= smart_date($comment['created']); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
	</div>
</div>
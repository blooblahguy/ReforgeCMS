<?php

?>
<div class="post">
	<div class="post_header padx1 bg-dark">
		<div class="row g1 content-middle">
			<div class="os-min padx2">
				<div class="avatar_icon">
					<img src="<?= get_user($app['author'])->avatar; ?>" alt="">
				</div>
			</div>
			<div class="os">
				<div class="post_title strong">
					<?= the_title($app['id']); ?>
				</div>
				<div class="post_info">
					<span class="user"><?= the_author($app['id']); ?></span>
					<span class="date small muted">on <?= the_date($app['id']); ?></span>
				</div>
			</div>
		</div>
		<div class="user_info row g1">
			
		</div>
	</div>
	<div class="post_content bg-black pad2 pady1">
		<?
		render_entry_results($app['id']);
		?>
	</div>
</div>

<?
	post_comments($app['id']);
?>

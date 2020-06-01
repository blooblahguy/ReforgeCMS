<?php

class Comments extends \Prefab {
	function __construct() {
		global $core;
		$core->route("POST /comments/submit/@post_id", "Comments->submit");
		$core->route("GET /comments/delete/@comment_id", "Comments->delete");
	}

	function submit($core, $args) {
		$post_id = $args['post_id'];
		if (user_can_comment($post_id)) {
			$comment = new Comment();
			$comment->post_id = $post_id;
			$comment->message = $_POST['message'];
			$comment->author = current_user()->id;
			$comment->save();

			redirect();
		} else {
			\Alerts::instance()->error("You don't have permission to do that.");
			redirect();
		}
		
	}

	function delete($core, $args) {
		$id = $args['comment_id'];

		// debug($args);
		// debug($id);
		$comment = new Comment();
		$comment->load("*", array("id = :id", ":id" => $id));
		// debug($comment);


		// exit();

		if (current_user()->can("manage_comments") || current_user()->id == $comment->author) {
			$comment->erase();
			redirect(false, "#comments");
		} else {
			Alerts::instance()->error("You don't have permission to do that");
			redirect();
		}
	}
}

function user_can_comment($post_id) {
	$user = current_user();
	$post = new Post();
	$post->load("disable_comments", array("id = :id", ":id" => $post_id));

	// debug($post);

	if ($post->disable_comments) {
		return false;
	}
	if ($user->can("manage_comments")) {
		return true;
	}
	return $post->is_visible();
}

function post_comments($post_id) {
	if (! user_can_comment($post_id)) { return; }

	?>
	<div id="comments" class="comments pady2">
		<h4 class="bg-dark pad1 marg0">Comments</h4>
		<?

		$comments = new Comment();
		$comments = $comments->find("*", array("post_id = :pid", ":pid" => $post_id));

		foreach ($comments as $comment) {
			$author = new User();
			$author = $author->load("username, class", array("id = :id", ":id" => $comment['author']));

			?>
			<div class="post_comment padx1" id="comment<?= $comment['id']; ?>">
				<div class="row g1">
					<div class="os-min">
						<span class="date muted"><?= smart_date($comment['created']); ?></span>
					</div>
					<div class="os-min">
						<span class="user"><strong class="<?= $author['class']; ?>"><?= $author['username']; ?></strong></span>
					</div>
					<div class="os message">
						<?= $comment['message'] ?>
					</div>
				</div>
				<? if (current_user()->can("manage_comments") || $comment['author'] == current_user()->id) { ?>
					<div class="comment_manage overlay">
						<a class="ibtn" href="/comments/delete/<?= $comment['id']; ?>"><i>delete</i></a>
						<a class="ibtn edit_comment" href="#0"><i>edit</i></a>
					</div>
				<? } ?>
			</div>
			<?
		}

		// display new comment thread
		?>
		<div class="add_comment padt1">
			<form method="POST" action="/comments/submit/<?= $post_id; ?>">
				<div class="row g1 bg-light">
					<div class="os">
						<input type="text" name="message">
					</div>
					<div class="os-min">
						<input type="submit">
					</div>
				</div>
			</form>
		</div>
	</div>
	<?
}

new Comments();
<?php

class Comments extends \Prefab {
	function __construct() {
		global $core;
		// $core->route();
	}

	function submit($core, $args) {

	}
}

function post_comments($post_id) {
	$comments = new Comment();
	$comments = $comments->find(array("post_id = :pid", ":pid" => $post_id));

	debug($comments);

	// display new comment thread
	?>
	<div class="add_comment">
		<h3>Add Comment</h3>
		<form action="/comments/submit">
			<textarea name="" id="" cols="30" rows="10" class="simple_editor"></textarea>
			<input type="submit">
		</form>
	</div>
	<?
}
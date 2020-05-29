<?
$user = new User();
$user->get_user($comment['author']); 
?>
<p>Your application has updated activity. You can view your application <a href="https://bigdumb.gg/recruitment/applications/<?= $post_id; ?>">here</a></p>
<div style="background: rgba(0,0,0,.1)">
	<p style="margin: 20px 0 0 0; padding: 20px; font-weight:bold;"><?= $user['username']; ?> Says:</p>
	<p style="padding: 20px 0; margin:0; "><?= $comment['message']; ?></p>
</div>
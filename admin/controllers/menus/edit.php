<?
	$id = $core->get("post_id");
	// $user = new User();
	$action = "Create";
	$subject = "Menu";
	if ($id > 0) {
		// $user->load("id = $id");
		$action = "Edit";
		$subject = ucfirst($user->username);
	}

	$roles = $db->query("SELECT * FROM roles ORDER BY `priority` ASC");
?>

<form action="<?= $core->get("admin_path"); ?>/users/save/<?= $id; ?>" method="POST" class="row">
	<div class="os">
		<div class="row content-middle">
			<div class="os-min padr2">
				<h2 class="marg0"><? echo sprintf($core->get("page_title"), $action, $subject); ?></h2>
			</div>
			<div class="os padl2">
				<a href="post_types/edit/0" class="btn">New Menu</a>
			</div>
		</div>

	
	</div>

	<div class="os-2 sidebar pad3">
		<input type="submit" class="marg0" value="Save">
		<hr>
	</div>
</form>
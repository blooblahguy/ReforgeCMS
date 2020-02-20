<?
	$id = $core->get("post_id");
	$roles = $db->query("SELECT * FROM roles ORDER BY menu_order ASC");
?>

<form action="<?= $core->get("admin_path"); ?>/users/save/<?= $id; ?>" method="POST" class="row">
	<div class="os">
		<div class="row content-middle">
			<div class="os-min padr2">
				<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
			</div>
			<div class="os padl2">
				<a href="post_types/edit/0" class="btn">New User</a>
			</div>
		</div>

		<div class="content pad2 padl0">
			<div class="padb2">
				<label for="">Username <span>*</span></label>
				<input type="text" name="username" value="" required placeholder="Username">
			</div>
			<div class="padb2">
				<label for="">Email <span>*</span></label>
				<input type="text" name="email" value="" required placeholder="Email">
			</div>
			<div class="padb2">
				<label for="">Role <span>*</span></label>
				<select name="role_id" required>
					<? foreach ($roles as $role) { ?>
						<option value="<?= $role["id"]; ?>"><?= $role["label"]; ?></option>
					<? } ?>
				</select>
			</div>

		
		</div>
	</div>

	<div class="os-2 sidebar pad3">
		<input type="submit" class="marg0" value="Save">
		<hr>
	</div>
</form>
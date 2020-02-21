<?
	$id = $core->get("post_id");

	$post = new \PostType();

	$action = "Create";
	$subject = "Post Type";

	if ($id > 0) {
		$post->load("id = $id");
		$action = "Edit";
		$subject = $post->label;
	}
	if (! $post["statuses"]) {
		$post["statuses"] = serialize(array());
	}
	$statuses = unserialize($post["statuses"]);

	$icons = array();
	$icons[] = "sms";
	$icons[] = "create";
	$icons[] = "assignment_ind";
	$icons[] = "gamepad";
	$icons[] = "build";
	$icons[] = "delete";
	$icons[] = "alarm";
	$icons[] = "account_circle";
	$icons[] = "calendar_today";
	$icons[] = "code";
	$icons[] = "done";
	$icons[] = "extension";
	$icons[] = "question_answer";
	$icons[] = "room";
	$icons[] = "schedule";
	$icons[] = "settings";
	$icons[] = "visibility";
	$icons[] = "timelines";
	$icons[] = "translate";
	$icons[] = "grade";
	$icons[] = "error";
	$icons[] = "link";
	$icons[] = "folder";
	$icons[] = "collections";
	$icons[] = "tune";
	$icons[] = "shopping_cart";
	$icons[] = "layers";
	$icons[] = "power";
	$icons[] = "apartment";
	$icons[] = "storefront";
	$icons[] = "star";
	$icons[] = "view_compact";
	$icons[] = "category";
	$icons[] = "poll";
?>

<form action="<?= $core->get("admin_path"); ?>/post_types/save/<?= $id; ?>" method="POST" class="row">
	<div class="os">
		<div class="row content-middle">
			<div class="os-min padr2">
				<h2 class="marg0"><? echo sprintf($core->get("page_title"), $action, $subject); ?></h2>
			</div>
			<div class="os padl2">
				<a href="/admin/post_types/edit/0" class="btn">Create Post Type</a>
			</div>
		</div>

		<div class="content pad2 padl0">
			<label for="">Slug <span>*</span></label>
			<input type="text" name="slug" value="<?= $post["slug"]; ?>" required placeholder="Slug">

			<div class="row g2">
				<div class="os">
					<label for="">Label <span>*</span></label>
					<input type="text" name="label" value="<?= $post["label"]; ?>" required placeholder="Label">
				</div>
				<div class="os">
					<label for="">Plural Label <span>*</span></label>
					<input type="text" name="label_plural" value="<?= $post["label_plural"]; ?>" required placeholder="Plural Label">
				</div>
				
			</div>
			<label for="">Description</label>
			<textarea name="description"><?= $post["description"]; ?></textarea>

			<h3>Access</h3>
			<div class="row g2">
				<div class="os">
					<input type="checkbox" name="public" value="1" <? if ($post->public) { echo "checked"; } ?>> Publicly Viewable
					<input type="checkbox" name="searchable" value="1" <? if ($post->searchable) { echo "checked"; } ?>> Include in Search
				</div>
			</div>

			<div class="row content-middle">
				<div class="os-min padr2">
					<h3 class="marg0">Statuses</h3>
				</div>
				<div class="os padl2">
					<a href="#" data-template=".statuses" data-target=".status_rows" data-index="<?= count($statuses) || 0; ?>" class="btn btn-sm">+</a>
				</div>
			</div>

			<div class="status_rows padx1">
				<? foreach ($statuses as $k => $status) { ?>
					<div class="row g1 content-middle 1">
						<div class="os-min">
							<input type="radio" name="default_status" value="<?= $k; ?>" <? if ($status["default_status"]) {echo "checked"; } ?>> Default
						</div>
						<div class="os">
							<input type="text" name="statuses[<?= $k; ?>][name]" value="<?= $status["name"]; ?>" placeholder="Name">
						</div>
						<div class="os">
							<select name="statuses[<?= $k; ?>][status]">
								<option <? if ($status["status"] == "Published") { echo "selected"; }?>>Published</option>
								<option <? if ($status["status"] == "Draft") { echo "selected"; }?>>Draft</option>
								<option <? if ($status["status"] == "Archive") { echo "selected"; }?>>Archive</option>
							</select>
						</div>
						<div class="os-min remove">
							<a href="#" data-remove=".row" class="btn btn-sm">-</a>
						</div>
					</div>
				<? } ?>
			</div>

			<template class="statuses">
				<div class="row g1 content-middle 1">
					<input type="hidden" name="new_status[]" value="1">
					<div class="os-min">
						<input type="radio" name="default_status" value="$i"> Default
					</div>
					<div class="os">
						<input type="text" name="name[]" value="" placeholder="Name">
					</div>
					<div class="os">
						<select name="status[]">
							<option>Published</option>
							<option>Draft</option>
							<option>Archive</option>
						</select>
					</div>
					<div class="os-min remove">
						<a href="#" data-remove=".row" class="btn btn-sm">-</a>
					</div>
				</div>
			</template>
		</div>
	</div>

	<div class="os-2 sidebar pad3">
		<input type="submit" class="marg0" value="Save">
		<hr>
		<div class="pady2 padt0">
			<label for="">Icon</label>
			<select name="icon" class="border" multiple>
				<? foreach ($icons as $k => $i) { 
					if ($k % 6 == 0) {?> 
						<option value="" class="break"></option>
					<? } ?>
					<option class="material-icons" value="<?= $i; ?>" <? if ($post->icon == $i) {echo "selected"; } ?>><?= $i; ?></option>
				<? } ?>
			</select>
		</div>
		<div class="pady2">
			<label for="">Admin Menu</label>
			<input type="checkbox" name="admin_menu" value="1" <? if ($post->admin_menu) { echo "checked"; }?>> Display in Admin Menu
			<label for="">Menu Position</label>
			<input type="text" name="order" value="<?= $post->order; ?>">
		</div>
		
	</div>
</form>
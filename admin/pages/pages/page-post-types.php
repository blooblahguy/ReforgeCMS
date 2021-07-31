<?php

class admin_page_POSTTYPES extends RF_Admin_Page {
	function __construct() {
		$this->name = "post_types";
		$this->label = "Post Type";
		$this->label_plural = "Post Types";
		$this->admin_menu_parent = "settings";
		$this->admin_menu = 70;
		$this->icon = "web";
		$this->permissions = array(
			"all" => "manage_post_types"
		);
		$this->link = "/admin/{$this->name}";


		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {

		$post_types = new PostType();
		$post_types = $post_types->find("*", null, array("order by" => "`order` ASC"));
		echo '<div class="section">';
		display_results_table($post_types, array(
			'icon' => array(
				"label" => "",
				"class" => "min",
				"html" => '<i class="material-icons">%1$s</i>',
			),
			'label_plural' => array(
				"label" => "Name",
				"class" => "tablelabel",
				"html" => '<a href="post_types/edit/%2$d">%1$s</a>',
			),
			'slug' => array(
				"label" => "Slug",
				"html" => '<a href="post_types/edit/%2$d">%1$s</a>',
			),
			'children' => array(
				"label" => "Children",
			),
			'id' => array(
				"label" => "ID",
				"class" => "min"
			),
			'admin_menu' => array(
				"label" => "Admin Menu",
				"class" => "min",
				"calculate" => function($string, $id) {
					if ($string == 1) {
						return "Yes";
					} else {
						return "No";
					}
				}
			),
			'order' => array(
				"label" => "Order",
				"class" => "min"
			),
		));
		echo '</div>';
	}

	function edit($args) {
		$this->render_title();

		$id = $this->id;
		$post = new \PostType();
		if ($id > 0) {
			$post->load("*", array("id = :id", ":id" => $id));
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

		<div class="row g1">
			<div class="os">
				<div class="section">
					<?
					render_html_field($post, array(
						"type" => "text",
						"name" => "slug",
						"label" => "Slug",
						"required" => true,
					));
					?>
					<div class="row g2">
						<?
						render_html_field($post, array(
							"type" => "text",
							"name" => "label",
							"label" => "Label",
							"required" => true,
						));
						render_html_field($post, array(
							"type" => "text",
							"name" => "label_plural",
							"label" => "Plural Label",
							"required" => true,
						));
						?>
					</div>
					<?
					render_html_field($post, array(
						"type" => "textarea",
						"name" => "description",
						"label" => "Description",
					));
					?>
				</div>
				<div class="section">
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
							<a href="#" data-template=".statuses" data-target=".status_rows" data-replace="index" data-index="<?= count($statuses) || 0; ?>" class="btn btn-sm">+</a>
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
								<input type="radio" name="default_status" value="$index"> Default
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

			<div class="os-2 sidebar">
				<div class="section">
					<input type="submit" class="marg0" value="Save">
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

					<?
					render_html_field($post, array(
						"type" => "checkbox",
						"name" => "admin_menu",
						"label" => "Admin Menu",
					));

					render_html_field($post, array(
						"type" => "text",
						"name" => "order",
						"label" => "Admin Menu Position",
					));

					render_html_field($post, array(
						"type" => "checkbox",
						"name" => "allow_parents",
						"label" => "Allow Post Parenting",
					));
					render_html_field($post, array(
						"type" => "text",
						"name" => "url_prefix",
						"label" => "URL Prefix",
					));
					?>
				
				</div>
			</div>
		</div>
	<?
	}

	function save($args) {
		$id = $this->id;
		$type = new \PostType();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$type->load("*", array("id = :id", ":id" => $id));
		}

		$default_status = $_POST["default_status"];
		$statuses = repeater_existing("statuses");
		$new_statuses = repeater_new("new_status", "name", "status");
		$statuses = array_merge($statuses, $new_statuses);
		if (isset($default_status)) {
			$statuses[$default_status]["default_status"] = 1;
		}

		$type->slug = $_POST['slug'];
		$type->label = $_POST['label'];
		$type->label_plural = $_POST['label_plural'];
		$type->url_prefix = slugify($_POST['url_prefix']);
		$type->description = $_POST['description'];
		$type->admin_menu = $_POST['admin_menu'];
		$type->public = $_POST['public'];
		$type->searchable = $_POST['searchable'];
		$type->order = $_POST['order'];
		$type->icon = $_POST['icon'];
		$type->statuses = serialize($statuses);
		$type->save();

		\Alerts::instance()->success("Post type {$type->slug} $changed");
		redirect("/admin/post_types/edit/{$type->id}");
	}

	function delete($args) {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

new admin_page_POSTTYPES();


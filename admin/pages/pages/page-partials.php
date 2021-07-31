<?php

class admin_page_PARTIALS extends RF_Admin_Page {
	function __construct() {
		$this->name = "partials";
		$this->label = "Partial";
		$this->label_plural = "Partials";
		$this->admin_menu = 25;
		$this->icon = "widgets";
		$this->permissions = array(
			"all" => "manage_partials"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) { 
		$this->render_title();
		?>
		<div class="section">
		<?
		$partials = new Partial();
		$partials = $partials->find("*", "post_type = 'partial' "); 
		// display table
		display_results_table($partials, array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
		)); 
		?>
		</div>
		<?
	}

	function edit($args) {
		$this->render_title();
		$id = $this->id;
		$partial = new Partial();
		if ($id > 0) {
			$partial->load("*", array("id = :id", ":id" => $id));
		}

		$cache = get_meta("partial_$id", 'cache');

		?>
		<div class="row g1">
			<div class="os main">
				<div class="section">
					<?
					

					render_html_field($partial, array(
						"type" => "text",
						"label" => "Title",
						"name" => "title",
						"class" => "post_title",
						"required" => true,
					));
					
					render_html_field($partial, array(
						"type" => "wysiwyg",
						"label" => "Content",
						"name" => "content",
						"layout" => "padt1",
						"style" => "height: 300px",
					));
					?>
				</div>

				<? do_action("admin/custom_fields", "partial"); ?>
			</div>
			<div class="os-400px sidebar">
				<div class="section autosticky">
					<input type="submit" value="save">

					<? 
					render_html_field($cache, array(
						"type" => "select",
						"label" => "Cache HTML For:",
						"name" => "cache",
						"class" => "padt1",
						"required" => true,
						"choices" => array(
							(0) => "Disabled",
							(60) => "1 minute",
							(60 * 5) => "5 minutes",
							(60 * 30) => "30 minutes",
							(60 * 60) => "1 hour",
							(60 * 60 * 6) => "6 hours",
							(60 * 60 * 12) => "12 hours",
						),
					));
					render_html_field(array(), array(
						"type" => "select",
						"label" => "Permissions",
						"name" => "permissions",
						"choices" => array(),
						"class" => "post_file padt1 inline",
						"multiple" => true,
					));
					render_html_field($partial, array(
						"type" => "text",
						"label" => "Slug",
						"name" => "slug",
						"class" => "post_permalink padt1",
						"required" => false,
					));
					render_html_field(array(), array(
						"type" => "text",
						"label" => "Post File",
						"name" => "post_file",
						"class" => "post_file padt1 inline",
						"instructions" => "If this file exists in your theme, then it will be used to render the partials HTML. Otherwise the content field is used.",
						"disabled" => true,
					));
					?>
				</div>
			</div>
		</div>
		<?
	}

	function save($args) {
		$id = $this->id;

		$partial = new Partial();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$partial->load("*", array("id = :id", ":id" => $id));
		}

		$partial->title = $_POST['title'];
		$partial->slug = $_POST['slug'];
		$partial->content = $_POST['content'];
		$partial->post_type = "partial";
		$partial->author = current_user()->id;
		$partial->save();
		
		// $partial->update_cache = $_POST['update_cache'];


		RCF()->save_fields("partial", $partial->id);
		$uid = "partial_{$partial->id}";

		set_meta($uid, 'cache', $_POST['cache']);

		$this->save_success($partial->title, $changed, $partial->id);
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

new admin_page_PARTIALS();


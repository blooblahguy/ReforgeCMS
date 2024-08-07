<?php

class admin_page_MEDIA extends RF_Admin_Page {
	function __construct() {
		$this->name = "media";
		$this->label = "Media";
		$this->label_plural = "Media";
		$this->admin_menu = 1;
		$this->icon = "image";
		$this->permissions = array(
			"all" => "upload_files"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {
		$media = Media::instance();

		$media->display();
	}

	function edit($args) {
		
	}

	function save($args) {

	}

	function delete($args) {
		$id = $args['id'];
		$file = new File();
		$file->load("*", array("id = :id", ":id" => $id));
		$file->erase();

		\Alerts::instance()->warning("Media File $file->name removed");
		redirect($this->link);
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

new admin_page_MEDIA();


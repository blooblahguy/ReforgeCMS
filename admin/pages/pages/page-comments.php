<?php

class admin_page_COMMENTS extends RF_Admin_Page {
	function __construct() {
		$this->name = "comments";
		$this->label = "Comment";
		$this->label_plural = "Comments";
		$this->admin_menu = 40;
		$this->icon = "forums";
		$this->permissions = array(
			"all" => "manage_comments"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {
		
	}

	function edit($args) {
		
	}

	function save($args) {

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

new admin_page_COMMENTS();


<?php

class rcf_rule_ADMINPAGE extends rcf_rule {
	public
	$name = "",
	$label = "",
	$action = "",
	$rule_class = "";

	function __construct() {
		$this->name = 'post_type';
		$this->label = "Admin Page";
		$this->action = "admin/page/edit_after";
		$this->rule_class = __CLASS__;

		// now register in parent
		parent::__construct();
	}

	function rule_match( $request, $rule ) {
		return $this->compare( $request['page_slug'], $rule );
	}

	function rule_choices() {
		$admin_pages = RF_Admin_Pages::instance()->pages;
		usort( $admin_pages, function ($a, $b) {
			return $a->admin_menu > $b->admin_menu;
		} );
		foreach ( $admin_pages as $slug => $page ) {
			$choices[ $page->name ] = $page->label_plural;
		}

		return $choices;
	}
}


<?php

class rcf_rule_FORM extends rcf_rule {
	function __construct() {
		$this->name = 'form';
		$this->label = "Form";
		$this->rule_class = __CLASS__;

		// now register in parent
		parent::__construct();
	}

	function rule_match($request, $rule) {
		return $this->compare($request['form_id'], $rule);
	}

	function rule_choices() {
		$admin_pages = RF_Admin_Pages::instance()->pages;
		usort($admin_pages, function($a, $b) {
			return $a->admin_menu > $b->admin_menu;
		});
		foreach ($admin_pages as $slug => $page) {
			$choices[$page->name] = $page->label_plural;
		}

		return $choices;
	}
}


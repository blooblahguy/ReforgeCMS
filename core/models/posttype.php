<?php

class PostType extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"slug" => array(
				"type" => "VARCHAR(190)",
				"unique" => true,
			),
			"label" => array(
				"type" => "VARCHAR(256)"
			),
			"label_plural" => array(
				"type" => "VARCHAR(256)"
			),
			"description" => array(
				"type" => "LONGTEXT"
			),
			"public" => array(
				"type" => "INT(1)",
				"default" => 1
			),
			"searchable" => array(
				"type" => "INT(1)",
				"default" => 1
			),
			"icon" => array(
				"type" => "VARCHAR(100)",
				"default" => "create"
			),
			"admin_menu" => array(
				"type" => "INT(1)"
			),
			"order" => array(
				"type" => "INT(3)",
				"default" => 10
			),
			"statuses" => array(
				"type" => "LONGTEXT"
			),
			"url_prefix" => array(
				"type" => "VARCHAR(200)"
			),
			"allow_parents" => array(
				"type" => "INT(1)",
				"attrs" => "NOT NULL DEFAULT 0",
			),
		);

		parent::__construct("rf_post_types", $schema);
	}

	function get_admin_post_pages() {			
		$cpts = $this->find("*", null, array(
			"order by" => "`order` ASC",
		));

		return $cpts;
	}
}

function get_post_type($slug) {
	global $rf_custom_posts;

	foreach ($rf_custom_posts as $pt) {
		if ($pt['slug'] == $slug) {
			return $pt;
		}
	}
}

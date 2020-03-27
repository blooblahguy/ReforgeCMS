<?
	
	class Post extends \RF\Mapper {
		function __construct() {
			$schema = array(
				"post_type" => array(
					"type" => "VARCHAR(100)"
				),
				"post_parent" => array(
					"type" => "INT(7)",
				),
				"title" => array(
					"type" => "VARCHAR(280)",
				),
				"subtitle" => array(
					"type" => "VARCHAR(512)",
				),
				"content" => array(
					"type" => "LONGTEXT"
				),
				"seo_title" => array(
					"type" => "VARCHAR(70)"
				),
				"seo_category" => array(
					"type" => "VARCHAR(70)"
				),
				"seo_description" => array(
					"type" => "VARCHAR(256)"
				),
				"slug" => array(
					"type" => "VARCHAR(190)",
				),
				"permalink" => array(
					"type" => "VARCHAR(190)",
				),
				"author" => array(
					"type" => "INT(7)",
				),
				"post_parent" => array(
					"type" => "INT(7)",
				),
				"post_status" => array(
					"type" => "VARCHAR(250)",
				),
			);

			parent::__construct("posts", $schema);
		}
	}

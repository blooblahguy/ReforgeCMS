<?
	class Post extends RF_Model {
		function __construct() {
			$this->model_table = "posts";
			$this->model_schema = array(
				"post_type" => array(
					"type" => "VARCHAR(100)"
				),
				"post_parent" => array(
					"type" => "INT(7)",
				),
				"title" => array(
					"type" => "VARCHAR(256)",
				),
				"subtitle" => array(
					"type" => "VARCHAR(512)",
				),
				"content" => array(
					"type" => "LONGTEXT"
				),
				"seo_title" => array(
					"type" => "LONGTEXT"
				),
				"seo_description" => array(
					"type" => "LONGTEXT"
				),
				"slug" => array(
					"type" => "VARCHAR(256)",
				),
				"permalink" => array(
					"type" => "VARCHAR(256)",
					"unique" => true
				),
				"author" => array(
					"type" => "INT(7)",
				),
				"post_parent" => array(
					"type" => "INT(7)",
				),
				"post_status" => array(
					"type" => "VARCHAR(256)",
				),
			);

			parent::__construct();
		}
	}
?>
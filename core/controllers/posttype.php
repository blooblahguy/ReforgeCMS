<?

	class PostType extends RF_Model {
		function __construct() {

			$this->model_table = "post_types";
			$this->model_schema = array(
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
					"type" => "INT(1)"
				),
			);

			parent::__construct();
		}

		function get_admin_post_pages() {			
			$cpts = $this->select("*", "admin_menu = 1", array(
				"order" => "`order` ASC",
			));

			return $cpts;
		}
	}

?>
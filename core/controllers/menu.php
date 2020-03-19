<?
	class Menu extends RF_Model {
		function __construct() {
			$this->model_table = "menus";
			$this->model_schema = array(
				"slug" => array(
					"type" => "VARCHAR(190)",
					"unique" => true
				),
				"label" => array(
					"type" => "VARCHAR(256)"
				),
				"links" => array(
					"type" => "LONGTEXT"
				),
				"order" => array(
					"type" => "INT(3)"
				)
			);

			parent::__construct( );
		}

		function get_menu_array() {
			if (! $this->id) { return null; }

			$links = unserialize($this->links);

			return $links;
		}
	}
?>
<?

	class PostType extends RF_Model {
		function __construct() {

			$this->model_table = "post_types";

			parent::__construct();
		}

		function get_admin_post_pages() {
			global $db; 
			
			$cpts = $db->exec("SELECT * FROM {$this->model_table} WHERE admin_menu = 1 ORDER BY `order` ASC");

			return $cpts;
		}
	}

?>
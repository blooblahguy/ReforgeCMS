<?
	class Comment extends RF_Model {
		function __construct() {
			$this->model_table = "comments";
			$this->model_schema = array();

			parent::__construct();
		}
	}
?>
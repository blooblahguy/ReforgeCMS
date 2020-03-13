<?

class admin_page_MEDIA extends RF_Admin_Page {
	function __construct() {
		$this->name = "media";
		$this->label = "Media";
		$this->label_plural = "Media";
		$this->admin_menu = 1;
		$this->icon = "image";
		$this->base_permission = "upload_files";
		$this->link = "/admin/{$this->name}";

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		$media = RF_Media::instance();

		$media->display();
		// global $db;
		// $file = new File();
		// $media = $db->exec("SELECT * FROM {$file->model_table} ORDER BY modified DESC");
		// debug($media);

	}

	function render_edit() {
		
	}

	function save_page($core, $args) {

	}

	function delete_page($core, $args) {

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

new admin_page_MEDIA();

?>
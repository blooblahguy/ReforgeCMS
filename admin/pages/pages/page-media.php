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
		$this->disable_header = true;

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		$media = RF_Media::instance();

		$media->display();


	}

	function render_edit($core, $args) {
		
	}

	function save_page($core, $args) {

	}

	function delete_page($core, $args) {
		$id = $args['id'];
		$file = new RF_File();
		$file->load("id = $id");
		$file->erase();

		\Alerts::instance()->warning("Media File $file->name removed");
		redirect($this->link);
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


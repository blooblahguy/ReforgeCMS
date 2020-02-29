<?

class admin_page_SETTINGS extends RF_Admin_Page {
	function __construct() {
		$this->name = "settings";
		$this->label = "Settings";
		$this->label_plural = "Settings";
		$this->admin_menu = 85;
		$this->icon = "settings";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		echo "settings";
	}

	function render_edit() {
		echo "edit";
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

new admin_page_SETTINGS();

?>
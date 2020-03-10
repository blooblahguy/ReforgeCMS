<?

class admin_page_PLUGINS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Settings";
		$this->name = "plugins";
		$this->label = "Plugin";
		$this->label_plural = "Plugins";
		$this->admin_menu = 90;
		$this->icon = "settings_input_svideo";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		echo "widgets index";
	}

	function render_edit() {
		
	}

	function save_page() {

	}

	function delete_page() {

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

new admin_page_PLUGINS();

?>
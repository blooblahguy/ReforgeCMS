<?

class admin_page_DASHBOARD extends admin_page {
	function __construct() {
		$this->name = "dashboard";
		$this->label = "Dashboard";
		$this->label_plural = "Dashboard";
		$this->admin_menu = 0;
		$this->icon = "speed";
		$this->base_permission = "access_admin";
		$this->link_base = "/admin";
		$this->route_base = "/";
		$this->disable_header = true;

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index($core, $args) {
		echo "index";
	}

	protected function render_edit($core, $args) {
		echo "index";
	}

	protected function save_page($core, $args) {

	}

	protected function delete_page($core, $args) {

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

new admin_page_DASHBOARD();

?>
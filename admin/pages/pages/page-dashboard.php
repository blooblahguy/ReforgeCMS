<?

class admin_page_DASHBOARD extends RF_Admin_Page {
	function __construct() {
		global $core; 

		$this->name = "dashboard";
		$this->label = "Dashboard";
		$this->label_plural = "Dashboard";
		$this->admin_menu = 0;
		$this->icon = "speed";
		$this->base_permission = "access_admin";
		$this->link = "/admin/dashboard";
		$this->disable_header = true;

		// CUSTOM Routes (index, edit, and save are automatically created)
		$core->route("GET /", "admin_page_DASHBOARD->goto_dashboard");

		// Be sure to set up the parent
		parent::__construct();
	}

	function goto_dashboard($core, $args) {
		$core->reroute("/dashboard");
	}

	function render_index() {
		echo "index dashboard";
	}

	function render_edit() {
		echo "edit dashboard";
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

new admin_page_DASHBOARD();

?>
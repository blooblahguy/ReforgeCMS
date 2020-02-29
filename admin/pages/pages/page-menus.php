<?

class admin_page_MENUS extends RF_Admin_Page {
	function __construct() {
		$this->name = "menus";
		$this->label = "Menu";
		$this->label_plural = "Menus";
		$this->admin_menu = 50;
		$this->icon = "menus";
		$this->base_permission = "manage_menus";
		$this->link = "/admin/{$this->name}";

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		
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

new admin_page_MENUS();

?>
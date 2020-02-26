<?

class admin_page_MENUS extends admin_page {
	function __construct() {
		$this->name = "menus";
		$this->label = "Menu";
		$this->label_plural = "Menus";
		$this->admin_menu = 50;
		$this->icon = "menus";
		$this->base_permission = "manage_menus";
		$this->link_base = "/admin/{$this->name}";

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index($core, $args) {
		
	}

	protected function render_edit($core, $args) {
		
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

new admin_page_MENUS();

?>
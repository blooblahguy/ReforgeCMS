<?

class admin_page_WIDGETS extends admin_page {
	function __construct() {
		$this->name = "widgets";
		$this->label = "Widget";
		$this->label_plural = "Widgets";
		$this->admin_menu = 80;
		$this->icon = "widgets";
		$this->base_permission = "manage_widgets";
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

new admin_page_WIDGETS();

?>
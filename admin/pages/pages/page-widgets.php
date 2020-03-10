<?

class admin_page_WIDGETS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Design";
		$this->name = "widgets";
		$this->label = "Widget";
		$this->label_plural = "Widgets";
		$this->admin_menu = 80;
		$this->icon = "widgets";
		$this->base_permission = "manage_widgets";
		$this->link = "/admin/{$this->name}";

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

new admin_page_WIDGETS();

?>
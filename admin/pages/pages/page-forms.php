<?

class admin_page_FORMS extends admin_page {
	function __construct() {
		$this->name = "forms";
		$this->label = "Form";
		$this->label_plural = "Forms";
		$this->admin_menu = 45;
		$this->icon = "message";
		$this->base_permission = "manage_forms";
		$this->link = "/admin/{$this->name}";

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index() {
		
	}

	protected function render_edit() {
		
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

new admin_page_FORMS();

?>
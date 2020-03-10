<?

class admin_page_COMMENTS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Content";
		$this->name = "comments";
		$this->label = "Comment";
		$this->label_plural = "Comments";
		$this->admin_menu = 40;
		$this->icon = "forums";
		$this->base_permission = "manage_comments";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

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

new admin_page_COMMENTS();

?>
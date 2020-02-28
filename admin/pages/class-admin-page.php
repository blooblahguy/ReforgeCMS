<?

class admin_page {
	/**
	 * Permission Functionality
	 */
	function can_view($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_edit($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_save($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_delete($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}

	function __construct() {
		global $core, $user, $admin_menu;

		if (! $this->link) {
			$this->link = "/admin/{$this->name}";
		}

		if (! $this->route) {
			$this->route = $this->link;
		}

		// allow user to do anything here
		if (! $this->can_view()) {
			return false;
		}

		// INDEX
		$core->route("GET @{$this->name}_index: {$this->route}", "RF_Admin_Pages->index");
		// EDIT
		$core->route("GET @{$this->name}_edit: {$this->route}/edit/@id", "RF_Admin_Pages->edit");
		// // SAVE
		$core->route("GET @{$this->name}_save: {$this->route}/save/@id", "RF_Admin_Pages->save");
		// // DELETE
		$core->route("GET @{$this->name}_delete: {$this->route}/delete/@id", "RF_Admin_Pages->delete");

		// Register with RF_Admin
		RF_Admin_Pages::instance()->register_page($this);
	}

	/**
	 * View Rendering
	 */
	protected function render_title() {

		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link" => $this->link,
				"icon" => $this->icon,
				"id" => $this->id
			));
		}
	}

	function before_index($core, $args) {
		$this->render_title();

		do_action("admin/content");
		$this->render_index($core, $args);		
	}
	function before_edit($core, $args) {
		// page title
		$this->render_title();

		echo "<form action='{$this->link_base}/save/{$this->id}' method='POST'>";
			$this->render_edit($core, $args);
		echo "</form>";
	}

	function before_save() {

	}

	function before_delete() {

	}
}

?>
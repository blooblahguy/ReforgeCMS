<?

class RF_Admin_Page {
	function __construct() {
		global $core, $current_user, $admin_menu;

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
		// allow user to do anything here
		if ($this->base_permission) {
			if (! current_user()->can($this->base_permission)) {
				return false;
			}
		}

		// INDEX
		$core->route("GET @{$this->name}_index: {$this->route}", "RF_Admin_Pages->index");
		// EDIT
		$core->route("GET @{$this->name}_edit: {$this->route}/edit/@id", "RF_Admin_Pages->edit");
		// SAVE
		$core->route("POST @{$this->name}_save: {$this->route}/save/@id", "RF_Admin_Pages->save");
		// DELETE
		$core->route("GET|POST @{$this->name}_delete: {$this->route}/delete/@id", "RF_Admin_Pages->delete");

		// Register with RF_Admin
		RF_Admin_Pages::instance()->register_page($this);
	}

	/**
	 * get_id
	 * 
	 * Prefixes id with user_ID or rule_ID if page is another other than a post_type
	 * 
	 * @type	function
	 * @date	3/2/2020
	 * @since	1.0
	 * 
	 * @return $id (int)
	 */
	function get_id() {
		if ($this->is_post) {
			return $this->id;
		} else {
			return $this->name."_".$this->id;
		}
	}

	/**
	 * Permission Functionality
	 */
	function can_view($args = array()) {
		global $current_user;
		if (! $current_user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_edit($args = array()) {
		global $current_user;
		if (! $current_user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_save($args = array()) {
		global $current_user;
		if (! $current_user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	function can_delete($args = array()) {
		global $current_user;
		if (! $current_user->can($this->base_permission)) {
			return false;
		}
		return true;
	}

	function render_title() {

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

	function save_success($title, $changed, $id) {
		\Alerts::instance()->success("{$this->label} $title $changed");
		redirect("{$this->link}/edit/{$id}");
	}
}

?>
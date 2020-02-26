<?

class admin_page {
	function __construct() {
		global $core, $user, $admin_menu;

		if (! isset($this->base_permission)) {
			debug("Permissions is required when setting up page {$this->name}");
			return false;
		}

		// allow user to do anything here
		if (! $this->can_view()) {
			return false;
		}

		// add to user menu
		if (isset($this->admin_menu) && $this->admin_menu !== false) {
			$admin_menu[$this->admin_menu] = array(
				"label" => $this->label,
				"icon" => $this->icon,
				"link" => $this->link_base,
			);
		}

		/**
		 * Default routes
		 */
		// Index
		$core->route("GET {$this->link_base}", function($core, $args) { 
			if ($this->can_view($args)) {
				$this->pre_render_index($core, $args);
			}
		});

		// Edit / Create
		$core->route("GET {$this->link_base}/edit/@id", function($core, $args) { 
			if ($this->can_edit($args)) {
				$this->pre_render_edit($core, $args);
			}
		});

		// Save
		$core->route("POST {$this->link_base}/save/@id", function($core, $args) { 
			if ($this->can_save($args)) {
				$this->save_page($core, $args);
			}
		});

		// DELETE
		$core->route("POST|GET {$this->link_base}/delete/@id", function($core, $args) { 
			if ($this->can_delete($args)) {
				$this->delete_page($core, $args);
			}
		});
	}

	/**
	 * Permission Functionality
	 */
	protected function can_view($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	protected function can_edit($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	protected function can_save($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}
	protected function can_delete($args = array()) {
		global $user;
		if (! $user->can($this->base_permission)) {
			return false;
		}
		return true;
	}

	/**
	 * View Rendering
	 */
	private function pre_render_index($core, $args) {
		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link_base" => $this->link_base,
			));
		}

		// render child view
		$this->render_index($core, $args);
	}
	private function pre_render_edit($core, $args) {
		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link_base" => $this->link_base,
				"id" => $args["id"]
			));
		}

		// render child view
		$this->render_edit($core, $args);
	}
}

?>
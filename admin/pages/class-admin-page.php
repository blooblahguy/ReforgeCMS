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

		/**
		 * Default routes
		 */
		$this->route_base = isset($this->route_base) ? $this->route_base : $this->link_base;		
		// Index
		$core->route("GET {$this->route_base}", function($core, $args) { 
			global $request;
			$request["page_id"] = $args["id"];
			$request["page_name"] = $this->name;
			if ($this->can_view($args)) {
				add_action("admin/content", array($this, "pre_render_index"));
				// $this->pre_render_index($core, $args);
			}
		});

		// Edit / Create
		$core->route("GET {$this->route_base}/edit/@id", function($core, $args) { 
			global $request;
			$request["page_id"] = $args["id"];
			$request["page_name"] = $this->name;
			if ($this->can_edit($args)) {
				add_action("admin/content", array($this, "pre_render_index"));
				// $this->pre_render_edit($core, $args);
			}
		});

		// Save
		$core->route("POST {$this->route_base}/save/@id", function($core, $args) { 
			if ($this->can_save($args)) {
				add_action("admin/content", array($this, "pre_render_index"));
				// $this->save_page($core, $args);
			}
		});

		// DELETE
		$core->route("POST|GET {$this->route_base}/delete/@id", function($core, $args) { 
			if ($this->can_delete($args)) {
				add_action("admin/content", array($this, "pre_render_index"));
				// $this->delete_page($core, $args);
			}
		});

		// Register with Parent
		admin_pages::instance()->register_page($this);
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
	function pre_render_index() {

		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link_base" => $this->link_base,
				"icon" => $this->icon,
			));
		}
		
		// action before
		do_action("admin/page/index_before", $this->name);
		// render child view
		$this->render_index();
		// action after
		do_action("admin/page/index_after", $this->name);
	}
	function pre_render_edit() {
		

		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link_base" => $this->link_base,
				"icon" => $this->icon,
			));
		}

		// Form Start
		echo "<form action='{$this->link_base}/save/{$args['id']}' method='POST'>";
			// action before
			do_action("admin/page/edit_before", $this->name);
			// render child view
			$this->render_edit();
			// action after
			do_action("admin/page/edit_after", $this->name);
		// Form End
		echo "</form>";
	}
}

?>
<?

	class RF_Admin_Pages extends Prefab {
		var $pages = array();

		function beforeroute($core, $args) {
			global $request;

			$alias = $core->ALIAS;
			$alias = str_replace("_index", "", $alias);
			$alias = str_replace("_edit", "", $alias);
			$alias = str_replace("_save", "", $alias);
			$alias = str_replace("_delete", "", $alias);

			$this->page = $this->get_page($alias);
			$this->page->id = isset($args["id"]) ? $args["id"] : 0;

			$request["page_id"] = $this->page->id;
			$request["page_uid"] = $this->page->get_id();
			$request["page_slug"] = $this->page->name;

			// header
			do_action("admin/before_header", $this->page->name);
			include("views/header.php");
		}
		
		function afterroute($core, $args) {
			global $request;

			// lastly, footer
			do_action("admin/before_footer");
			queue_script("/admin/js/admin.js", 15);
			include("views/footer.php");
		}

		function register_page($class) {
			global $admin_menu, $core;

			// register by class name, then we can split the route name
			$this->pages[ $class->name ] = $class;

			// add to user menu
			if (isset($class->admin_menu) && $class->admin_menu !== false) {
				$admin_menu[$class->admin_menu] = array(
					"label" => $class->label_plural,
					"icon" => $class->icon,
					"link" => $class->link,
				);
			}
		}

		function get_page($slug) {
			return $this->pages[$slug];
		}

		function index($core, $args) {
			if ($this->page->can_view()) {
				$this->page->render_title();
				do_action("admin/page/index_before", $this->page);
				$this->page->render_index($core, $args);
				do_action("admin/page/index_after", $this->page);
			}
		}
		function edit($core, $args) {
			if ($this->page->can_edit()) {
				$this->page->render_title();
				echo "<form action='{$this->page->route}/save/{$this->page->id}' method='POST'>";
					do_action("admin/page/edit_before", $this->page);
					$this->page->render_edit($core, $args);
					do_action("admin/page/edit_after", $this->page);
				echo "</form>";
			}
		}
		function save($core, $args) {
			if ($this->page->can_save()) {
				do_action("admin/page/save_before", $this->page);
				$this->page->save_page($core, $args);
				do_action("admin/page/save_after", $this->page);
			}
		}
		function delete($core, $args) {
			if ($this->page->can_delete()) {
				do_action("admin/page/delete_before", $this->page);
				$this->page->delete_page($core, $args);
				do_action("admin/page/delete_after", $this->page);
			}
		}
	}

?>
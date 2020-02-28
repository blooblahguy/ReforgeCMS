<?

	class RF_Admin_Pages extends \Prefab {
		private $pages = array();

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
			$request["page_name"] = $this->page->name;			

			// header
			do_action("admin/before_header");
			require_once("views/header.php");
		}
		
		function afterroute($core, $args) {
			global $request;

			// lastly, footer
			do_action("admin/before_footer");
			queue_script("/admin/js/admin.js", 15);
			require_once("views/footer.php");
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
				do_action("admin/page/index_before", $this->page);
				$this->page->before_index($core, $args);
				do_action("admin/page/index_after", $this->page);
			}
		}
		function edit($core, $args) {
			if ($this->page->can_edit()) {
				do_action("admin/page/edit_before", $this->page);
				$this->page->before_edit($core, $args);
				do_action("admin/page/edit_after", $this->page);
			}
		}
		function save($core, $args) {
			if ($this->page->can_save()) {
				do_action("admin/page/save_before", $this->page);
				$this->page->before_save($core, $args);
				do_action("admin/page/save_after", $this->page);
			}
		}
		function delete($core, $args) {
			if ($this->page->can_delete()) {
				do_action("admin/page/delete_before", $this->page);
				$this->page->before_delete($core, $args);
				do_action("admin/page/delete_after", $this->page);
			}
		}
	}

?>
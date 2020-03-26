<?

	class RF_Admin_Pages extends Prefab {
		var $pages = array();
		var $pending_children = array();

		function beforeroute($core, $args) {
			global $request;

			$alias = $core->ALIAS;
			$alias = str_replace("_index", "", $alias);
			$alias = str_replace("_edit", "", $alias);
			$alias = str_replace("_save", "", $alias);
			$alias = str_replace("_delete", "", $alias);

			$this->page = $this->get_page($alias);
			$this->page->id = isset($args["id"]) ? (int) $args["id"] : 0;

			$user = current_user();
			if ($this->page->base_permission) {
				if (! $user->can($this->page->base_permission)) {
					exit();
				}
			}

			$request["page_id"] = $this->page->id;
			$request["page_uid"] = $this->page->get_id();
			$request["page_title"] = $this->page->label_plural;
			$request["page_slug"] = $this->page->name;

			// header
			do_action("admin/before_header", $this->page->name);
			require "views/header.php";
		}
		
		function afterroute($core, $args) {
			global $request;

			// lastly, footer
			do_action("admin/before_footer");
			require "views/footer.php";
		}

		function build_menus() {
			global $admin_menu;

			usort($admin_menu, function($a, $b) {
				if ($a->admin_menu_parent == $b->admin_menu_parent) {
					return $a->admin_menu > $b->admin_menu;
				}
				return $a->admin_menu_parent > $b->admin_menu_parent;
			});

			$final = array();
			foreach ($admin_menu as $class) {
				$entry = array(
					"label" => $class->label_plural,
					"icon" => $class->icon,
					"link" => $class->link,
					"order" => $class->admin_menu,
					'children' => array(),
				);
				if (! $class->admin_menu_parent) {
					$final[$class->name] = $entry;
				} else {
					$final[$class->admin_menu_parent]['children'][] = $entry;
				}
			}

			$admin_menu = $final;
		}

		function register_page($class) {
			global $admin_menu, $core;

			// register by class name, then we can split the route name
			$this->pages[ $class->name ] = $class;

			// add to user menu
			if (isset($class->admin_menu)) {
				$admin_menu[] = $class;
			}
		}

		function get_page($slug) {
			return $this->pages[$slug];
		}

		function index($core, $args) {
			if ($this->page->can_view()) {
				echo '<div class="page_content">';
					$this->page->render_title();
					do_action("admin/page/index_before", $this->page);
					$this->page->render_index($core, $args);
					do_action("admin/page/index_after", $this->page);
				echo '</div>';
			}
		}
		function edit($core, $args) {
			if ($this->page->can_edit()) {
				echo "<form action='{$this->page->route}/save/{$this->page->id}' method='POST' class='page_content' enctype='multipart/form-data'>";
					$this->page->render_title();
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


		function delete_page($core, $args) {
			$page = $this->page;
			$id = $this->page->id;

			
			if ($id > 0) {

			}
		}
	}

?>
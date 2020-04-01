<?

class RF_Admin_Pages extends \Prefab {
	var $pages = array();
	var $pending_children = array();

	function beforeroute($core, $args) {
		global $request;
		
		// get page info from alias
		$alias = $core->ALIAS;
		list($page_slug, $page_method) = explode("___", $alias);
	
		// set object
		$this->page = $this->get_page($page_slug);
		if (! $this->page) {

		}
		$this->page->id = isset($args["id"]) ? (int) $args["id"] : 0;
		$this->page->slug = $page_slug;
		$this->page->method = $page_method;

		$user = current_user();
		if ($this->page->base_permission) {
			if (! $user->can($this->page->base_permission)) {
				exit();
			}
		}

		$request["page_id"] = $this->page->id;
		$request["page_uid"] = $this->page->get_uid();
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
			if ($a['parent'] == $b['parent']) {
				return $a['order'] > $b['order'];
			}
			return $a['parent'] > $b['parent'];
		});

		// debug($admin_menu);

		$final = array();
		foreach ($admin_menu as $entry) {
			$entry['children'] = array();
			// $uid = md5(serialize($entry));

			// var_dump($entry['parent']);
			
			if (! $entry['parent']) {
				$final[strtolower($entry['label'])] = $entry;
			} else {
				$final[strtolower($entry['parent'])]['children'][] = $entry;
			}
		}

		$admin_menu = $final;
	}

	function register_page($class) {
		// register by class name, then we can split the route name
		$this->pages[ $class->name ] = $class;

		// add to user menu
		if (isset($class->admin_menu) && $class->admin_page !== false) {
			add_admin_menu(array(
				"label" => $class->label_plural,
				"order" => $class->admin_menu,
				"icon" => $class->icon,
				"link" => $class->link,
				"parent" => $class->admin_menu_parent,
			));
		}
	}

	function get_page($slug) {
		return $this->pages[$slug];
	}

	function route($core, $args) {
		// debug($args);
		$this->page->route($this->page->method, $core, $args);
	}
}


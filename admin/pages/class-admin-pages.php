<?php

class RF_Admin_Pages extends \Prefab {
	var $pages = array();
	var $pending_children = array();

	var $page;

	function beforeRoute( $core, $args ) {
		global $request;

		// get page info from alias
		$alias = $core->ALIAS;
		list( $page_slug, $page_method ) = explode( "___", $alias );

		// set object
		// debug( $this->pages );
		$this->page = $this->get_page( $page_slug );

		if ( ! $this->page || ! $this->page->can_view() ) {
			return false;
		}

		$this->page->id = isset( $args["id"] ) ? (int) $args["id"] : 0;
		$this->page->slug = $page_slug;
		$this->page->method = $page_method;
		if ( method_exists( $this->page, "query_object" ) ) {
			$this->page->query_object( $args );
		}

		$request["page_id"] = $this->page->id;
		$request["page_uid"] = $this->page->get_uid();
		$request["page_title"] = $this->page->label_plural;
		$request["page_slug"] = $this->page->name;


		// header
		do_action( "admin/before_header", $this->page->name );
		require "views/header.php";
	}

	function afterRoute( $core, $args ) {
		global $request;

		// lastly, footer
		do_action( "admin/before_footer" );
		require "views/footer.php";
	}

	function build_menus() {
		global $admin_menu;

		usort( $admin_menu, function ($a, $b) {
			if ( $a['parent'] == $b['parent'] ) {
				return $a['order'] > $b['order'] ? 1 : -1;
			}
			return $a['parent'] > $b['parent'] ? 1 : -1;
		} );

		$final = array();
		foreach ( $admin_menu as $entry ) {
			$entry['children'] = array();

			if ( ! $entry['parent'] ) {
				$final[ strtolower( $entry['label'] ) ] = $entry;
			} else {
				$final[ strtolower( $entry['parent'] ) ]['children'][] = $entry;
			}
		}

		$admin_menu = $final;
	}

	function register_page( $class ) {
		if ( ! $class || ! $class->can_view() ) {
			return false;
		}

		// register by class name, then we can split the route name
		$this->pages[ $class->name ] = $class;

		// add to user menu
		if ( isset( $class->admin_menu ) && $class->admin_page !== false ) {
			add_admin_menu( array(
				"label" => $class->label_plural,
				"order" => $class->admin_menu,
				"icon" => $class->icon,
				"link" => $class->link,
				"parent" => $class->admin_menu_parent,
			) );
		}
	}

	function get_page( $slug ): RF_Admin_Page {
		return $this->pages[ $slug ];
	}

	function route( $core, $args ) {
		$this->page->execute_route( $this->page->method, $args );
	}
}


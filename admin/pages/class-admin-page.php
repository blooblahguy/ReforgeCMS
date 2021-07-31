<?php

class RF_Admin_Page {
	function __construct() {
		global $core;

		// allow user to do anything here
		if (! $this->can_view()) { return false; }

		// defaults
		$this->routes = isset($this->routes) ? $this->routes : array();
		$this->link = isset($this->link) ? $this->link : "/admin/{$this->name}";

		// set default routes
		$this->routes = array_merge(array(
			"index" => array("GET", "", "index"),
			"edit" => array("GET", "/edit/@id", "edit"),
			"save" => array("POST", "/save/@id", "save"),
			"delete" => array("GET", "/delete/@id", "delete"),
		), $this->routes);

		// automatically register routes with named routed
		foreach ($this->routes as $key => $route) {
			list($header, $ext, $method) = $route;
			// echo "{$this->link}{$ext}";
			// echo "<br>";
			$core->route("$header @{$this->name}___$method: {$this->link}{$ext}", "RF_Admin_Pages->route");
		}

		// Register with RF_Admin
		RF_Admin_Pages::instance()->register_page($this);
	}

	function route($method, $args) {
		// do a global and automatic permission check
		if ($method == "edit" && ! $this->can_edit()) {
			return false;	
		} elseif ($method == "save" && ! $this->can_save()) {
			return false;
		} elseif ($method == "delete" && ! $this->can_delete()) {
			return false;
		} elseif (! $this->can_view()) {
			return false;
		}

		if (method_exists($this, "pre_".$method)) {
			$this->{"pre_".$method}($args);
		}
		$this->{$method}($args);
		if (method_exists($this, "post_".$method)) {
			$this->{"post_".$method}($args);
		}
	}

	/**
	 * Check ability to render index
	 */
	// function index($core, $args) {
	// 	echo "sit";
	// 	if (! $this->can_view()) { return false; }

	// 	$this->render_title();
	// 	$this->render_index($core, $args);
	// }
	function pre_edit($args) {
		global $core;
		$save_url = $this->link;
		$save_url .= $core->build($this->routes['save'][1], $args);
		// debug($save_url);
		echo "<form action='{$save_url}' method='POST' enctype='multipart/form-data'>";
	}
	function post_edit($args) {
		echo "</form>";
	}
	// function save($core, $args) {
	// 	if (! $this->can_save()) { return false; }

	// 	$this->save_page($core, $args);
	// }
	// function delete($core, $args) {
	// 	if (! $this->can_delete()) { return false; }

	// 	$this->delete_page($core, $args);
	// }

	/**
	 * get_id
	 * 
	 * Prefixes id with user_ID or rule_ID if page is another other than a post_type
	 * 
	 * @return $id (int)
	 */
	function get_uid() {
		if ($this->is_post) {
			return $this->id;
		} else {
			return $this->name."_".$this->id;
		}
	}

	function check_permission($ask) {
		return current_user()->can($ask);
	}

	/**
	 * Checks if user can view index of page
	 */
	function can_view() {
		if ($this->permissions['all']) {
			return $this->check_permission($this->permissions['all']);
		} elseif ($this->permissions['view']) {
			return $this->check_permission($this->permissions['view']);
		}

		return false;
	}

	/**
	 * Checks if user can view index of page
	 */
	function can_edit() {
		if ($this->permissions['all']) {
			return $this->check_permission($this->permissions['all']);
		} elseif ($this->permissions['update']) {
			return $this->check_permission($this->permissions['update']);
		} elseif ($this->object && $this->permissions['update_own']) {
			if (current_user()->id == $this->object->author) {
				return $this->check_permission($this->permissions['update_own']);
			}
		}

		return false;
	}

	/**
	 * Checks if user can view index of page
	 */
	function can_save() {
		if ($this->permissions['all']) {
			return $this->check_permission($this->permissions['all']);
		} elseif ($this->permissions['update']) {
			return $this->check_permission($this->permissions['update']);
		}

		return false;
	}

	/**
	 * Checks if user can view index of page
	 */
	function can_delete() {
		if ($this->permissions['all']) {
			return $this->check_permission($this->permissions['all']);
		} elseif ($this->permissions['delete']) {
			return $this->check_permission($this->permissions['delete']);
		} elseif ($this->object && $this->permissions['delete_own']) {
			if (current_user()->id == $this->object->author) {
				return $this->check_permission($this->permissions['delete_own']);
			}
		}

		return false;
	}


	/**
	 * Probably we move this at some point, renders page title + edit button if relevant
	 */
	function render_title($edit = true) {
		// display template header
		if (! $this->disable_header) {
			render_admin_title(array(
				"label" => $this->label,
				"plural" => $this->label_plural,
				"link" => $this->link,
				"icon" => $this->icon,
				"id" => $this->id
			), $edit);
		}
	}

	/**
	 * Probably redundant, stored message and redirects on its own
	 */
	function save_success($title, $changed, $id) {
		\Alerts::instance()->success("{$this->label} $title $changed");
		redirect("{$this->link}/edit/{$id}");
	}
}


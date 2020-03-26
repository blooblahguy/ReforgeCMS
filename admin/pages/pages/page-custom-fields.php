<?

class admin_page_CUSTOMFIELDS extends RF_Admin_Page {
	function __construct() {
		$this->name = "Settings";
		$this->name = "custom_fields";
		$this->label = "Custom Field";
		$this->label_plural = "Custom Fields";
		$this->admin_menu_parent = "settings";
		$this->admin_menu = 75;
		$this->icon = "filter_list";
		$this->base_permission = "manage_post_types";
		$this->link = "/admin/{$this->name}";

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index() {
		global $db;

		$fieldsets = $db->exec("SELECT * FROM custom_fields"); 

		echo '<div class="section">';
		display_results_table($fieldsets, array(
			"title" => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="/admin/custom_fields/edit/%2$d">%1$s</a>',
			),
		));
		echo '</div>';
	}

	function render_edit() {
		$id = $this->id;
		$cf = new CustomField();
		$action = "Create";
		$subject = "Custom Fields";
		$fields = array();
		$load_rules = array();
		if ($id > 0) {
			$cf->load("id = $id", null, 1);
			$action = "Edit";
			$subject = ucfirst($cf->title);
			$fields = unserialize($cf->fieldset);
			$load_rules = unserialize($cf->load_rules);
		}
		?>

		<div class="section">
			<label for="title">Title</label>
			<div class="row cfheader">
				<div class="os padr2">
					<input type="text" name="title" value="<?= $cf->title; ?>" placeholder="Title">
				</div>
				<div class="os-2">
					<input type="submit" class="marg0 btn-primary w100" value="Save">
				</div>
			</div>
		</div>

		<div class="section">
			<h2>Fields</h2>
			<?
			// Renders the fields
			do_action("rcf/admin_render_settings", $id);
			?>
		</div>
		<? 
		

		?>

		<div class="section">
			<h2>Load Conditions</h2>
			<?
			// render the rules
			do_action("rcf/admin_render_rules", $id);
			?>
		</div>
		<div class="section">
			<h2>Options</h2>
			<? 
			debug($cf->priority);
			render_admin_field($cf, array(
				"type" => "checkbox",
				"label" => "Active",
				"name" => "inactive",
				"value" => "1",
			));
			render_admin_field($cf, array(
				"type" => "select",
				"label" => "Display Priority",
				"name" => "priority",
				"choices" => array(
					3 => "Very High",	
					2 => "High",	
					0 => "Normal",	
					-1 => "Low",	
					-2 => "Very Low"	
				),
			));
			?>
		</div>
		<?
	}

	private function build_hierarchy($source) {
		$nested = array();
		if (! isset($source)) { return array(); }

		foreach ($source as &$field) {
			if ($field["parent"] == "0") {
				$nested[$field["key"]] = &$field;
			} else {
				$pid = $field["parent"];
				if ( isset($source[$pid]) ) {
					// If the parent ID exists in the source array
					// we add it to the 'children' array of the parent after initializing it.
					if ( !isset($source[$pid]['children']) ) {
						$source[$pid]['children'] = array();
					}
					$source[$pid]['children'][$field["key"]] = &$field;
				}
			}
		}

		return $nested;
	}

	function save_page($core, $args) {
		$id = $this->id;
		$cf = new CustomField();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$cf->load("id = $id");
		}

		$title = $_POST["title"];
		$fields = $_POST['rcf_fields'];
		$fieldset = $this->build_hierarchy($fields);

		// LOAD RULES
		$load_conditions = $_POST["load_conditions"];
		$rules = array();
		if (isset($load_conditions)) {
			foreach ($load_conditions as $group => $conditions) {
				$set = array();
				foreach ($conditions["key"] as $id => $value) {
					$set[$id]["key"] = $value;
				}
				foreach ($conditions["expression"] as $id => $value) {
					$set[$id]["expression"] = $value;
				}
				foreach ($conditions["value"] as $id => $value) {
					$set[$id]["value"] = $value;
				}

				$rules[$group] = $set;
			}		
		}

		$cf->title = $title;
		$cf->active = $_POST['priority'];
		$cf->priority = $_POST['inactive'];
		$cf->fieldset = serialize($fieldset);
		$cf->load_rules = serialize($rules);
		// exit();
		$cf->save();

		\Alerts::instance()->success("Custom Field $title $changed");
		redirect("/admin/custom_fields/edit/{$cf->id}");
	}

	function delete_page($core, $args) {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

new admin_page_CUSTOMFIELDS();

?>
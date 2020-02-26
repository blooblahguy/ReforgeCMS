<?

class admin_page_CUSTOMFIELDS extends admin_page {
	function __construct() {
		$this->name = "custom_fields";
		$this->label = "Custom Field";
		$this->label_plural = "Custom Fields";
		$this->admin_menu = 75;
		$this->icon = "filter_list";
		$this->base_permission = "manage_post_types";
		$this->link_base = "/admin/{$this->name}";

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	protected function render_index($core, $args) {
		global $db;

		$fieldsets = $db->exec("SELECT * FROM custom_fields"); 

		display_results_table($fieldsets, array(
			"title" => array(
				"label" => "Title",
				"html" => '<a href="/admin/custom_fields/edit/%2$d">%1$s</a>',
			),
		));
	}

	protected function render_edit($core, $args) {
		$id = $args["id"]; 
		$cf = new CustomField();
		$action = "Create";
		$subject = "Custom Fields";
		$fields = array();
		$load_rules = array();
		if ($id > 0) {
			$cf->load("id = $id");
			$action = "Edit";
			$subject = ucfirst($cf->title);
			$fields = unserialize($cf->fieldset);
			$load_rules = unserialize($cf->load_rules);
		}
		?>

		<form action="<?= $core->get("admin_path"); ?>/custom_fields/save/<?= $id; ?>" target="_blank" method="POST">
			<label for="title">Title</label>
			<div class="row margb2 cfheader">
				<div class="os padr2">
					<input type="text" name="title" value="<?= $cf->title; ?>" placeholder="Title">
				</div>
				<div class="os-2">
					<input type="submit" class="marg0" value="Save">
				</div>
			</div>

			<? 
			// Renders the fields
			do_action("rcf/admin_render_settings", $id); ?>
				
			<div class="load_rules padt2">
				<!-- <h3>Load Conditions</h3> -->

				<div class="fieldset load_conditions">
				<div class="row content-middle g1 padx1">
					<div class="os-2">
						<label for="">Load Conditions</label>
						<p class="description em">Show these fields if the following conditions are met</p>
					</div>
					<div class="os">
						<div class="row g1 condition_row">
							<div class="os">
								<select name="load_conditions[$i][key][]" class="load_key">
									<option value="post_type">Post Type</option>
								</select>
							</div>
							<div class="os-2">
								<select name="load_conditions[$i][expression][]" class="load_expression">
									<option value="==">Is Equal To</option>
									<option value="!=">Is Not Equal To</option>
								</select>
							</div>
							<div class="os">
								<select name="load_conditions[$i][value][]" class="load_value">

								</select>
							</div>
							<div class="os-min">
								<a href="#" class="btn bt-mini rcf_add_and">And</a>
							</div>
						</div>
						<a href="#" class="btn bt-mini rcf_add_and">Or</a>
					</div>
				</div>
				
				
			</div>

			<!-- <div class="load_rules pady2">
				<h3>Display Options</h3>
			</div> -->
		</form>

		<?
	}

	private function build_hierarchy($source) {
		$nested = array();

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

	protected function save_page($core, $args) {
		$id = $args["id"];
		$cf = new CustomField();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$cf->load("id = $id");
		}

		$title = $_POST["title"];
		$fields = $_POST['rcf_fields'];
		$fieldset = $this->build_hierarchy($fields);

		// load options

		// display options			

		$cf->title = $title;
		$cf->fieldset = serialize($fieldset);
		$cf->save();

		\Alerts::instance()->success("Custom Field $title $changed");
		redirect("/admin/custom_fields/edit/{$cf->id}");
	}

	protected function delete_page($core, $args) {

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
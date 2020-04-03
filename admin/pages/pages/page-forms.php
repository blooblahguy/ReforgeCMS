<?

class admin_page_FORMS extends RF_Admin_Page {
	function __construct() {
		$this->name = "forms";
		$this->label = "Form";
		$this->label_plural = "Forms";
		$this->admin_menu = 42;
		$this->icon = "email";
		$this->permissions = array(
			"all" => "manage_forms"
		);

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {
		$this->render_title();

		$forms = new Post();
		$forms = $forms->find("post_type = 'forms'");

		$columns = array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'author' => array(
				"label" => "Author",
				"calculate" => function($value, $id) {
					$user = get_user($value);
					return $user->username;
				}
			),
			'modified' => array(
				"label" => "Updated",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
			'created' => array(
				"label" => "Created",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
			'remove' => array (
				"label" => "Remove",
				"class" => "min",
				"calculate" => function($s, $id) {
					return "<a href='{$this->link}/delete/{$id}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		);

		$columns = apply_filters("admin/forms/columns", $columns);

		// display table
		?>
		<div class="section">
			<? display_results_table($forms, $columns);	?>
		</div>
		<?
	}
	
	function edit($args) {
		$id = $args['id'];
		$form = new Post();
		if ($id > 0) {
			$form->load("id = $id");
		}
		$rca_id = $form->post_parent;

		$this->render_title();
		
		?>
		<div class="row g1">
			<div class="os">
				<div class="section">
					<?
					render_admin_field($form, array(
						"type" => "text",
						"label" => "Label",
						"name" => "title",
					));
					render_admin_field($form, array(
						"type" => "textarea",
						"label" => "Instructions",
						"name" => "subtitle",
					));
					?>
				</div>
				<div class="section">
					<h2>Fields</h2>
					<?
					// Renders the fields
					do_action("rcf/admin_render_settings", $rca_id);
					?>
				</div>
			</div>
			<div class="os-2 sidebar">
				<div class="section">
					<input type="submit">
				</div>
			</div>
		</div>

		<?
	}

	function save($args) {
		global $custom_fields_page;
		// debug($custom_fields_page);
		$id = $args['id'];

		$form = new Post();
		if ($id > 0) {
			$form->load(array("id = :id", ":id" => $id));
		}

		// save custom fieldset
		$cf = $custom_fields_page->save(array("id" => $form->post_parent, "noredir" => true, "virtual" => true));

		// store form values
		$form->title = $_POST['title'];
		$form->subtitle = $_POST['subtitle'];
		$form->post_type = 'forms';
		$form->post_parent = $cf->id;
		$form->author = current_user()->id;
		$form->save();

		\Alerts::instance()->success("Custom Field {$_POST['title']} saved.");
		redirect("/admin/forms/edit/{$form->id}");
	}

	function delete($args) {

	}
}

new admin_page_FORMS();


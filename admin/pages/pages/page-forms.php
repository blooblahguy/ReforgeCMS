<?

class admin_page_FORMS extends RF_Admin_Page {
	function __construct() {
		global $core;

		$this->name = "forms";
		$this->label = "Form";
		$this->label_plural = "Forms";
		$this->admin_menu = 42;
		$this->icon = "email";
		$this->permissions = array(
			"all" => "manage_forms"
		);

		if ($this->can_view()) {
			$this->routes = array(
				"entries" => array("GET", "/entries/@id", "view_entries"),
				"entry" => array("GET", "/entries/@id/@entry", "view_entry"),
			);
		}

		// Be sure to set up the parent
		parent::__construct();
	}

	function view_entries($args) {
		$id = $args['id'];

		$entries = new Post();
		$entries = $entries->find("*", array("post_parent = :id AND post_type = :pt", ":id" => $id, ":pt" => "form_entry"));

		$columns = array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/entries/'.$id.'/%2$d">%1$s</a>',
			),
		);

		?>
		<div class="section">
			<? display_results_table($entries, $columns);	?>
		</div>

		<?
	}

	function view_entry($args) {
		debug($args);
	}

	function index($args) {
		$this->render_title();

		$forms = new Post();
		$forms = $forms->find("*", "post_type = 'forms'");

		$columns = array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'entries' => array(
				"label" => "Entries",
				"calculate" => function($value, $id) {
					$entries = new Post();
					$entries = $entries->find("*", array(
						"post_parent = $id", 
						"post_type = 'form_entry'"
					));

					return "<a href='{$this->link}/entries/{$id}'>View Entries (".count($entries).")</a>";
				}
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
			$form->load("*", "id = $id");
		}
		$rca_id = $form->post_parent;

		$this->render_title();
		
		?>
		<div class="row g1">
			<div class="os">
				<div class="section g1">
					<?
					render_html_field($form, array(
						"type" => "text",
						"label" => "Label",
						"name" => "title",
						"class" => "post_title"
					));
					render_html_field($form, array(
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
					<?

					render_html_field($form, array(
						"type" => "text",
						"label" => "Slug",
						"name" => "slug",
						"class" => "post_permalink"
					));
					?>
				</div>
			</div>
		</div>

		<?
	}

	function save($args) {
		$id = $args['id'];

		$form = new Post();
		if ($id > 0) {
			$form->load("*", array("id = :id", ":id" => $id));
		}

		// save custom fieldset
		$cf = new CustomField();
		if ($form->post_parent > 0) {
			$cf->load("*", "id = $form->post_parent");
		}
		$_POST['virtual_fieldset'] = true;
		$cf = $cf->save_fieldset();

		// store form values
		$form->title = $_POST['title'];
		$form->subtitle = $_POST['subtitle'];
		$form->slug = $_POST['slug'];
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


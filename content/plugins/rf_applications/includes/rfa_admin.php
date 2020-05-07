<?

class RFA_Applications_Admin extends RF_Admin_Page {
	function __construct() {
		// global $core;
		$this->name = "applications";
		$this->label = "Application";
		$this->label_plural = "Applications";
		$this->admin_menu = 30;
		$this->icon = "assignment_ind";
		$this->permissions = array(
			"all" => "manage_applications"
		);
		$this->routes = array(
			"settings" => array("GET", "/settings", "settings"),
			"settings_save" => array("POST", "/settings/save", "settings_save"),
		);

		// Be sure to set up the parent
		parent::__construct();

		if ($this->can_view()) {

			add_admin_menu(array(
				"label" => "Settings",
				"parent" => "Applications",
				"link" => "/admin/applications/settings",
			));
		}
	}

	function settings() {
		render_admin_header("Applications Settings");

		// $apply_page = get_option("rfa_apply_page");
		$apply_index = get_option("rfa_apply_index");
		$apply_page = get_option("rfa_apply_page");
		$apply_form = get_option("rfa_apply_form");
		
		$pages = new Post();
		$pages = $pages->find("post_type = 'pages' ");
		$pages = array_extract($pages, "id", "title");

		$forms = new Post();
		$forms = $forms->find("post_type = 'forms' ");
		$forms = array_extract($forms, "id", "title");

		?>
		<form method="POST" action="<?= $this->link; ?>/settings/save">
			<div class="row g1">
				<div class="os">
					<div class="section">
						<?
						render_admin_field($apply_index, array(
							"label" => "Applications Page",
							"default" => "/recruitment/applications",
							"type" => "select",
							"choices" => $pages,
							"name" => "rfa_apply_index",
							"instructions" => "Page for viewing, commenting, and editing applications"
						));
						render_admin_field($apply_page, array(
							"label" => "Apply Page",
							"default" => "/recruitment/apply",
							"type" => "select",
							"choices" => $pages,
							"name" => "rfa_apply_page",
							"instructions" => "Page for filling out and submitting a new application"
						));
						render_admin_field($apply_form, array(
							"label" => "Apply Form",
							"type" => "select",
							"choices" => $forms,
							"name" => "rfa_apply_form",
							"instructions" => "What form to use for new applications"
						));
						?>
					</div>
				</div>
				<div class="os-2 sidebar">
					<div class="section">
						<input type="submit">
					</div>
				</div>
			</div>

		</form>
		<?

		do_action("admin/custom_fields", "applications");
	}

	function settings_save($args) {
		// debug("here");
		set_option("rfa_apply_index", $_POST['rfa_apply_index']);
		set_option("rfa_apply_page", $_POST['rfa_apply_page']);
		set_option("rfa_apply_form", $_POST['rfa_apply_form']);

		\Alerts::instance()->success("Applications setttings updated");
		redirect($this->link."/settings");
	}
	
	function index() { 
		render_admin_header("Applications");

		$app = new RF_Applications\Model();
		$open = $app->find("post_type = 'application' AND post_status='open' ");
		$declined = $app->find("post_type = 'application' AND post_status='declined' ");
		$closed = $app->find("post_type = 'application' AND post_status='closed' ");
		?>

		<div class="section tabs">
			<div class="tab_nav">
				<a href="#0" data-tab="open">Open</a>
				<a href="#0" data-tab="declined">Declined</a>
				<a href="#0" data-tab="closed">Closed</a>
			</div>
			<div class="tab_content" data-tab="open">
				<? 
				display_results_table($open, array(
					'title' => array(
						"label" => "Title",
						"class" => "tablelabel",
						"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
					),
				)); 
				?>
			</div>
			<div class="tab_content" data-tab="declined">
				<? 
				display_results_table($declined, array(
					'title' => array(
						"label" => "Title",
						"class" => "tablelabel",
						"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
					),
				)); 
				?>
			</div>
			<div class="tab_content" data-tab="closed">
				<? 
				display_results_table($closed, array(
					'title' => array(
						"label" => "Title",
						"class" => "tablelabel",
						"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
					),
				)); 
				?>
			</div>
		</div>

		<?
	}

	function edit($args) {
		
	}

	function save($args) {

	}

	function delete_page() {

	}
}

new RFA_Applications_Admin();


// $post_type = array(
// 	"order" => 15,
// 	"slug" => "application",
// 	"label" => "Application",
// 	"label_plural" => "Applications",
// 	"class" => "admin_page_APPLICATIONS",
// 	"icon" => "assignment_ind",
// 	"statuses" => array(),
// );
// register_post_type($post_type);
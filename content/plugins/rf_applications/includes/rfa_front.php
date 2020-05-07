<?

class RFA_Applications_Front extends \Prefab {
	public $applications, $apply, $form;

	// hook into content
	// function beforeroute($core, $args) {
	// 	$page = array(
	// 		"title" => "Applications",
	// 		"subtitle" => "",
	// 		"slug" => "applications",
	// 		"id" => 0,
	// 	);

	// 	if ($core->get("PATTERN") == "/recruitment/applications") {
	// 		$page['title'] = "Applications";
	// 	}
	// 	if ($core->get("PATTERN") == "/recruitment/applications/@id") {
	// 		$page['title'] = "View Application";
	// 	}
	// 	if ($core->get("PATTERN") == "/recruitment/apply") {
	// 		$page['title'] = "Submit Application";
	// 	}

	// 	Content::instance()->page = $page;
	// 	Content::instance()->beforeroute($core, $args);
	// 	Content::instance()->page($core, $args);
	// }
	// function afterroute($core, $args) {
	// 	Content::instance()->afterroute($core, $args);
	// }

	function __construct() {
		global $core;

		// get endpoints
		$this->applications = get_option("rfa_apply_index");
		$this->apply = get_option("rfa_apply_page");
		$this->form = get_option("rfa_apply_form");

		// $page = new Post();
		// $page->load(array("id = :id", ":id" => ));
		// $this->applications = $page->slug;

		// $page->load(array("id = :id", ":id" => ));
		// $this->apply = $page->slug;

		// $page->load(array("id = :id", ":id" => $this->applications));
		// $this->form = $page->slug;

		// debug($page);

		// debug($this->apply);
		// debug($this->form);
		// debug($this->applications);

		

		add_action("page/{$this->applications}/content", array($this, "view"));
		add_action("page/{$this->apply}/content", array($this, "apply"));

		// $core->route("POST {$this->apply}", "RFA_Applications_Front->submit_application");
		// $core->route("GET /{$this->apply}", "RFA_Applications_Front->index_all");

		// if user is logged in, let them view the index
		// if (current_user()->logged_in()) {
			
			// Non members can submit new applications
			// if (! current_user()->can("view_applications")) {
			// 	$core->route("GET {$this->apply}", "RFA_Applications_Front->new_application");
			// 	$core->route("POST {$this->apply}", "RFA_Applications_Front->submit_application");
			// }
			
			// // those with permissions can view anything
			// if (current_user()->can("manage_applications") || current_user()->can("view_applications")) {
			// 	$core->route("GET {$this->applications}", "RFA_Applications_Front->index_all");
			// 	$core->route("GET {$this->applications}/@id", "RFA_Applications_Front->view");
			// } else {
			// 	// allow user to view any of their own apps
			// 	$core->route("GET {$this->applications}", "RFA_Applications_Front->index_mine");
			// 	$apps = $this->get_apps();
			// 	foreach ($apps as $app) {
			// 		$core->route("GET {$this->applications}/{$app['id']}", "RFA_Applications_Front->view");
			// 	}
			// }
		// }
	}

	/**
	 * Return list of all of this user's applications
	 */
	function get_apps() {
		$user = current_user();
		if (! $user->logged_in()) {
			return array();
		}

		$apps = new Post();
		$apps->find(array("post_type = :post_type AND author = :author", ":post_type" => "applications", ":author" => $user->id), array(
			"order by" => "post_status ASC"
		));
	}

	/**
	 * Return browsable index of applications
	 */
	function index_all() {
		?>
		<div class="section">
			<div class="container">
				No current applications
			</div>
		</div>
		<?
		echo "show all";
	}
	function index_mine() {
		$user = current_user();

			echo "show mine";

	}

	/**
	 * View specific application
	 */
	function view($page) {
		// $id = $args['id'];
		
		echo "view";
	}
	
	/**
	 * Show new application form
	 */
	function apply($page) {
		echo "new_application";
	}

	/**
	 * Save new application
	 */
	function submit_application() {

	}
}

if (current_user()->logged_in()) {
	$front = new RFA_Applications_Front();
}
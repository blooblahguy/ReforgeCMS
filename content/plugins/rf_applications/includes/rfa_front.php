<?

class RFA_Applications_Front extends \Prefab {
	public $applications, $apply, $form;

	function __construct() {
		global $core;
		$this->rfa = RFApps();

		// ROUTES
		if (logged_in()) {
			$core->route("GET /recruitment/applications/@id", "Content->page");
			$core->route("GET /recruitment/apply/@id", "Content->page");
		}

		// filters
		add_filter("page/title", array($this, "application_page"));
		add_filter("form/redirect", array($this, "submit_redirect"));	
	}

	function submit_redirect($redirect, $form_id, $entry_id) {
		if ($form_id == $this->rfa->form) {
			return $this->rfa->app_link($entry_id);
		}

		return $redirect;
	}

	/**
	 * Render a list of apps you can see
	 */
	function render_index() {
		$user = current_user();
		$app = new Post();

		if (! logged_in()) {
			redirect("/");
		}

		// load all applications
		if ($user->can("view_applications")) {
			$open = $app->find("*", "post_type = 'application' AND post_status = 'open' ");
			$other = $app->find("*", "post_type = 'application' AND post_status != 'open' ");
		// load my applications
		} else {
			$open = $app->find("*", "post_type = 'application' AND post_status = 'open' AND author = {$user->id} ");
			$other = $app->find("*", "post_type = 'application' AND post_status != 'open' AND author = {$user->id} ");
		}

		if (count($open) == 0 && logged_in()) { ?>
			<div class="row g1 content-middle">
				<div class="os">
					<div class="message-info">
						You don't have any open applications with BDG
					</div>
				</div>
				<div class="os-min">
					<?= $this->rfa->apply_button(); ?>
				</div>
			</div>
			
			<hr>
		<? }
		foreach ($open as $app) {
			include $this->rfa->path."/views/index_app.php";
		}
		foreach ($other as $app) {
			include $this->rfa->path."/views/index_app.php";
		}
	}

	function view_application($id) {
		$app = new Post();
		$app->load("*", array("id = :id", ":id" => $id));

		include $this->rfa->path."/views/view_app.php";
	}

	function application_page($title, $request, $args) {
		if ($request['page_id'] == $this->rfa->applications && $args['id']) {
			$app = new Post();
			$app->load("*", array("id = :id", ":id" => $args['id']));
			$title = $app->title;

		}
		return $title;
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
		$apps->find("*", array("post_type = :post_type AND author = :author", ":post_type" => "applications", ":author" => $user->id), array(
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
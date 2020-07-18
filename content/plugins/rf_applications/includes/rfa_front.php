<?

class RFA_Applications_Front extends \Prefab {
	public $applications, $apply, $form;
	private $character_name;

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

		add_action("group_results/after_render/application/character_name", array($this, "get_character_info"));
		add_action("group_results/after_render/application/character_server", array($this, "display_character_info"));
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
			$open = $app->find("*", "post_type = 'application' AND post_status = 'open' ", array(
				"order by" => "created DESC"
			));
			$other = $app->find("*", "post_type = 'application' AND post_status != 'open' ", array(
				"order by" => "created DESC"
			));
		// load my applications
		} else {
			$open = $app->find("*", "post_type = 'application' AND post_status = 'open' AND author = {$user->id} ", array(
				"order by" => "created DESC"
			));
			$other = $app->find("*", "post_type = 'application' AND post_status != 'open' AND author = {$user->id} ", array(
				"order by" => "created DESC"
			));
		}

		if (! $user->can("view_applications") && count($open) == 0 && logged_in()) { ?>
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
		<? } ?>
		<? if (count($open)) { ?>
			<div class="bg-dark pad1">Open Applications</div>
			<?
			foreach ($open as $app) {
				include $this->rfa->path."/views/index_app.php";
			}
		}
		if (count($other)) { ?>
			<div class="bg-dark pad1">Closed Applications</div>
			<? foreach ($other as $app) {
				include $this->rfa->path."/views/index_app.php";
			}
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

	function get_character_info($field, $data) {
		$this->character_name = $data['meta_value'];
	}
	function display_character_info($field, $data) {
		$server = $data['meta_value'];
		$character = $this->character_name;

		// debug($server);
		// debug($character);

		$realm = str_replace (" ", "-", $server);
		$realm = preg_replace ("/[^a-zA-Z-]/", "", $realm);

		$wcl = "https://www.warcraftlogs.com/character/us/{$realm}/{$character}";
		$armory = "https://worldofwarcraft.com/en-us/character/{$realm}/{$character}";
		$analyzer = "https://wowanalyzer.com/character/US/{$realm}/{$character}";
		$wipefest = "https://www.wipefest.net/character/{$character}/{$realm}/US";

		?>
		<div class="field os-12">
			<div class="formsec group_outer">
				<label for="">Sites</label>
				<div class="repeater_body custom_app_links">
					<div class="row">
						<a target="_blank" class="value repeater_entry pad1 os-lg os-md-4 os-12 text-primary text-center" href="<?= $armory; ?>">Armory</a>
						<a target="_blank" class="value repeater_entry pad1 os-lg os-md-4 os-12 text-primary text-center" href="<?= $wcl; ?>">Warcraft Logs</a>
						<a target="_blank" class="value repeater_entry pad1 os-lg os-md-4 os-12 text-primary text-center" href="<?= $analyzer; ?>">WoW Analyzer</a>
						<a target="_blank" class="value repeater_entry pad1 os-lg os-md-4 os-12 text-primary text-center" href="<?= $wipefest; ?>">Wipefest</a>
					</div>
				</div>
			</div>
		</div>
		<?php

		// return false;
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
			"order by" => "post_status ASC, id DESC"
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

function RFA_Front() {
	return RFA_Applications_Front::instance();
}

if (current_user()->logged_in()) {
	$front = RFA_Front();
}
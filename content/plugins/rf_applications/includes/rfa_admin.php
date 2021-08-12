<?php

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
		$apply_role = get_option("rfa_apply_role");
		
		$pages = new Post();
		$pages = $pages->find("*", "post_type = 'page' ");
		$pages = array_extract($pages, "id", "title");

		$forms = new Post();
		$forms = $forms->find("*", "post_type = 'forms' ");
		$forms = array_extract($forms, "id", "title");

		$roles = new Role();
		$roles = $roles->find("*");
		$roles = array_extract($roles, "id", "label");

		?>
		<form method="POST" action="<?= $this->link; ?>/settings/save">
			<div class="row g1">
				<div class="os">
					<div class="section">
						<?
						render_html_field($apply_index, array(
							"label" => "Applications Page",
							"default" => "/recruitment/applications",
							"type" => "select",
							"choices" => $pages,
							"name" => "rfa_apply_index",
							"instructions" => "Page for viewing, commenting, and editing applications"
						));
						render_html_field($apply_page, array(
							"label" => "Apply Page",
							"default" => "/recruitment/apply",
							"type" => "select",
							"choices" => $pages,
							"name" => "rfa_apply_page",
							"instructions" => "Page for filling out and submitting a new application"
						));
						render_html_field($apply_form, array(
							"label" => "Apply Form",
							"type" => "select",
							"choices" => $forms,
							"name" => "rfa_apply_form",
							"instructions" => "What form to use for new applications"
						));
						render_html_field($apply_role, array(
							"label" => "Approved User Role",
							"type" => "select",
							"choices" => $roles,
							"name" => "rfa_apply_role",
							"instructions" => "What role to assign to users who's applications are approved."
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
		set_option("rfa_apply_role", $_POST['rfa_apply_role']);

		\Alerts::instance()->success("Applications setttings updated");
		redirect($this->link."/settings");
	}
	
	function index() { 
		render_admin_header("Applications");

		$app = new Post();
		$open = $app->find("*", "post_type = 'application' AND post_status = 'open' ");
		$declined = $app->find("*", "post_type = 'application' AND post_status = 'declined' ");
		$closed = $app->find("*", "post_type = 'application' AND post_status = 'closed' ");
		$accepted = $app->find("*", "post_type = 'application' AND post_status = 'accepted' ");

		$columns = array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'author' => array(
				"label" => "User",
				"calculate" => function($value, $r) {
					the_author($r['id']);
				}
			),
			'submitted' => array(
				"label" => "Submitted",
				"calculate" => function($value, $r) {
					the_date($r['id']);	
				},
			),
			'remove' => array (
				"label" => "Remove",
				"class" => "min",
				"calculate" => function($s, $r) {
					return "<a href='{$this->link}/delete/{$r['id']}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		);

		?>

		<div class="section tabs">
			<div class="tab_nav">
				<a href="#0" data-tab="open">Open</a>
				<a href="#0" data-tab="accepted">Accepted</a>
				<a href="#0" data-tab="declined">Declined</a>
				<a href="#0" data-tab="closed">Closed</a>
			</div>
			<div class="tab_content" data-tab="open">
				<? 
				display_results_table($open, $columns); 
				?>
			</div>
			<div class="tab_content" data-tab="accepted">
				<? 
				display_results_table($accepted, $columns); 
				?>
			</div>
			<div class="tab_content" data-tab="declined">
				<? 
				display_results_table($declined, $columns); 
				?>
			</div>
			<div class="tab_content" data-tab="closed">
				<? 
				display_results_table($closed, $columns); 
				?>
			</div>
		</div>

		<?
	}

	function edit($args) {
		render_admin_header("View Application");
		$rfa = RFApps();
		$id = $args['id'];

		$app = new Post();
		if ($id) {
			$app->load("*", array("id = :id", ":id" => $id));
		}



		?>
		<div class="row g1">
			<div class="os">
				<div class="section">
					<div class="row g1">
						<div class="os">
						<?
							render_html_field($app, array(
								"type" => "text",
								"name" => "title",
								"label" => "Title",
								"required" => true,
							))
						?>
						</div>
						<div class="os">
							<?
							render_html_field($app, array(
								"type" => "select",
								"choices" => array(
									"open" => "Open",
									"closed" => "Closed",
									"accepted" => "Accepted",
									"declined" => "Declined",
								),
								"name" => "post_status",
								"label" => "Status",
								"required" => true,
							))
							?>
						</div>
					</div>
					
				</div>
				<? render_form($rfa->form, array(
					"type" => "application",
					"entry" => $args['id'],
					"hide_title" => true,
				));
				?>
			</div>
			<div class="os-3">
				<div class="sidebar autosticky">
					<div class="section">
						<a href="<?= $rfa->app_link($id); ?>" class="btn w100 margb1" target="_blank">View Application</a>
						<input type="submit" value="Save">
					</div>
					<div class="section">
						<div class="message_action">
							<label for="">Message & Action</label>
							<textarea name="message" placeholder="Required if changing app status"></textarea>
							<div class="row g1 padt2">
								<div class="os"><input type="submit" class="btn-success" name="action" value="Accept"></div>
								<div class="os"><input type="submit" class="btn-error" name="action" value="Decline"></div>
								<div class="os"><input type="submit" class="btn-warning" name="action" value="Close"></div>
								<div class="os"><input type="submit" class="btn-info" name="action" value="Open"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
	}

	function save($args) {
		$id = $args['id'];

		$app = new Post();
		if ($id > 0) {
			$app->load("*", array("id = :id", ":id" => $id));
		}

		$app->post_status = $_POST['post_status'];
		$title = $app->title;
		$title = preg_replace("/.*[-] /", "", $title);
		$title = ucfirst($app->post_status)." - ".$title;
		$app->title = $title;


		// run quick actions if set
		if (isset($_POST['action'])) {
			$action = $_POST['action'];
			$message = $_POST['message'];
			$statuses = array();
			$statuses['Accept'] = "accepted";
			$statuses['Close'] = "closed";
			$statuses['Decline'] = "declined";
			$statuses['Open'] = "open";

			$app->post_status = $statuses[$action];

			// load user and change role if necessary
			if ($app->post_status == "accepted") {
				$user = new User();
				$user->load("id, role_id", array("id = :id", ":id" => $app->author));
				$user->role_id = RFApps()->role;
				$user->save();
			}

			$title = $app->title;
			$title = preg_replace("/.*[-] /", "", $title);
			$title = ucfirst($app->post_status)." - ".$title;
			$app->title = $title;

			if (isset($message) && $message != "") {
				$comment = new Comment();
				$comment->post_id = $id;
				$comment->message = $message;
				$comment->author = current_user()->id;
				$comment->save();
			}
		}

		$app->save();

		// dispatch action if we altered status of the application
		if ($action) {
			do_action("application/".$action, $app);
		}

		RCF()->save_fields("application", $app->id);

		redirect("/admin/applications/edit/".$app->id);
	}

	function delete($args) {
		if ($this->can_delete()) {
			$post = new Post();
			$post->load("*", array("id = :id", ":id" => $args['id']));
			$post->erase();

			\Alerts::instance()->success("Deleted application");
			redirect($this->link);
		}
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
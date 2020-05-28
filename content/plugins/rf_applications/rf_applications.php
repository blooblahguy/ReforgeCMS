<?



class RFApplications extends \Prefab {
	function __construct() {
		global $core; 
		$this->applications = get_option("rfa_apply_index");
		$this->apply = get_option("rfa_apply_page");
		$this->form = get_option("rfa_apply_form");
		$this->path = dirname(__FILE__);

		// actions to load only necessary controllers
		add_action("admin/init", function() {
			require "includes/rfa_admin.php";
		});
		add_action("front/init", function() {
			require "includes/rfa_front.php";
		});

		// add permissions for managing and viewing applications
		add_permission(array(
			"slug" => "manage_applications",
			"label" => "Manage Applications",
			"description" => "Allow user to accept, decline, or close apps. They can also change the application format and settings."
		));
		add_permission(array(
			"slug" => "view_applications",
			"label" => "View Applications",
			"description" => "Allow users to view all guild applications"
		));

		// save our form as an application post type
		add_filter("form/submit", array($this, "save_status"));
		add_filter("form/submit/type", array($this, "save_as_application"));
	}

	function apply_button($text = "Apply Now") {
		$apply = new Post();
		$apply->load("*", array("id = :id", ":id" => $this->apply));
		?>
			<a class="btn-primary" href="<?= $apply->get_permalink(); ?>"><?= $text; ?></a>
		<?
	}
	function app_link($id) {
		$apps = new Post();
		$apps->load("*", array("id = :id", ":id" => $this->applications));

		return $apps->get_permalink()."/".$id;
	}

	function save_status($entry, $id) {
		if ($id == 0) {
			$user = get_user($entry->author);
			$entry->title = "Open - Application: ".$user->username;
			$entry->post_status = "open";
			$entry->permission = "view_applications";
			$entry->permission_exp = "==";
		}
		return $entry;
	}
	function save_as_application($type, $form_id) {
		debug($form_id, $type);
		if ($form_id == $this->form) {
			return "application";
		}

		return $type;
	}

	function front_view($core, $args) {
		
		// $content->page = $content->pages["/recruitment/applications"];
		// $content->page($core, $args);

		// debug($content);
		// debug($args);
	}
}

function RFApps() {
	return RFApplications::instance();
}
$rfa = RFApps();
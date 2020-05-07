<?



class RFApplications extends \Prefab {
	function __construct() {
		// include model
		require "includes/rfa_model.php";

		// actions to load only necessary controllers
		add_action("admin/init", array($this, "admin"));
		add_action("front/init", array($this, "front"));

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
	}

	function admin() {
		require "includes/rfa_admin.php";
	}
	function front() {
		require "includes/rfa_front.php";
	}

	/**
	 * To be run when plugin is first installed
	 */
	function install() {

	}

	/**
	 * To be run when plugin is first uninstalled
	 */
	function uninstall() {

	}

	/**
	 * To be run when plugin is activated
	 */
	function activate() {

	}

	/**
	 * To be run when plugin is deactivated
	 */
	function deactivate() {

	}
}

function RFApps() {
	return RFApplications::instance();
}
$rfa = RFApps();
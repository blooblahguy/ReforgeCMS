<?

class RF_Profiles_Admin extends RF_Admin_Page {
	function __construct() {
		$this->name = "profiles";
		$this->label = "RF Profile";
		$this->label_plural = "RF Profiles";
		$this->admin_menu = 100;
		$this->admin_menu_parent = "settings";
		$this->icon = "assignment_ind";
		$this->permissions = array(
			"all" => "manage_forms"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {
		$this->route("edit", $args);
	}

	function edit($args) {
		echo 'eedit';
	}

	function save() {

	}
}

new RF_Profiles_Admin();
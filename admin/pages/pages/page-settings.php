<?

class admin_page_SETTINGS extends RF_Admin_Page {
	function __construct() {
		$this->name = "settings";
		$this->label = "Settings";
		$this->label_plural = "Settings";
		$this->admin_menu = 85;
		$this->icon = "settings";
		$this->permissions = array(
			"all" => "manage_settings"
		);
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;
		$this->routes = array(
			"index" => array("GET", "", "edit") 
		);

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	// function index($core, $args) {
	// 	$this->edit($core, $args);
	// }

	function edit($args) {
		global $options;
		$pages = new Post();
		$pages = $pages->find("*", "post_type = 'pages'");

		$home_page = get_option("site_homepage");
		$disable_seo = get_option("disable_seo");
		$seo_seperator = get_option("seo_seperator");
		$seo_keywords = get_option("seo_keywords");
		$sitename = get_option("sitename");
		$default_role = get_option("default_role");

		?>
		<div class="row content-middle padb2">
			<div class="os-min padr2">
				<h2 class="marg0">Settings</h2>
			</div>
		</div>

		<div class="section">
			<div class="row g1">

				<? 
			$choices = array_extract($pages, "id", "title");
			render_html_field($home_page, array(
				"type" => "select",
				"label" => "Site Homepage",
				"name" => "site_homepage",
				"choices" => $choices,
			));
			
			$roles = new Role();
			$roles = $roles->find("*");
			$roles = array_extract($roles, "id", "label");
			render_html_field($default_role, array(
				"type" => "select",
				"label" => "Default Role",
				"name" => "default_role",
				"choices" => $roles,
			));
			
			?>
			</div>
		</div>

		<div class="section">
			<h2>SEO Settings</h2>
			<?
			render_html_field($disable_seo, array(
				"type" => "checkbox",
				"label" => "Discourage Search Engines",
				"name" => "disable_seo",
			));
			?>
			<div class="row g1">
				<?
				render_html_field($sitename, array(
					"type" => "text",
					"label" => "Site Title",
					"name" => "sitename",
				));
				render_html_field($seo_seperator, array(
					"type" => "text",
					"label" => "Title Tag Seperator",
					"default" => "|",
					"name" => "seo_seperator",
				));
				render_html_field($seo_keywords, array(
					"type" => "text",
					"label" => "Keywords (comma seperated)",
					"name" => "seo_keywords",
				));
				?>
			</div>
		</div>

		<? do_action("admin/custom_fields", "settings"); ?>

		<input type="submit">
		<?
	}

	function save($args) {
		// debug("here");
		set_option("seo_title", $_POST['seo_title']);
		set_option("site_homepage", $_POST['site_homepage']);
		set_option("disable_seo", $_POST['disable_seo']);
		set_option("seo_seperator", $_POST['seo_seperator']);
		set_option("seo_keywords", $_POST['seo_keywords']);
		set_option("default_role", $_POST['default_role']);

		RCF()->save_fields("settings", "0");

		\Alerts::instance()->success("Setttings updated");
		redirect($this->link);
	}

	function delete($args) {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

new admin_page_SETTINGS();


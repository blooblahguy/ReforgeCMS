<?

class admin_page_SETTINGS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Settings";
		$this->name = "settings";
		$this->label = "Settings";
		$this->label_plural = "Settings";
		$this->admin_menu = 85;
		$this->icon = "settings";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		// CUSTOM Routes (index, edit, and save are automatically created)

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index($core, $args) {
		RF_Admin_Pages::instance()->edit($core, $args);
	}

	function render_edit() {
		$pages = new Post();
		$pages = $pages->query("SELECT * FROM posts WHERE post_type = 'pages'");
		$home_page = get_option("site_homepage");
		$admin_theme = get_option("admin_theme");
		$disable_seo = get_option("disable_seo");

		?>
		<div class="row content-middle padb2">
			<div class="os-min padr2">
				<h2 class="marg0">Settings</h2>
			</div>
		</div>

		<? 
		$choices = array_extract($pages, "id", "title");
		render_admin_field($home_page, array(
			"type" => "select",
			"label" => "Site Homepage",
			"name" => "site_homepage",
			"choices" => $choices,
		));

		$choices = array("dark" => "Dark", "light" => "Light");
		render_admin_field($admin_theme, array(
			"type" => "select",
			"label" => "Admin theme",
			"name" => "admin_theme",
			"choices" => $choices,
		));

		render_admin_field($disable_seo, array(
			"type" => "checkbox",
			"label" => "Discourage Search Engines",
			"name" => "disable_seo",
		));
		
		?>

		<input type="submit">
		<?
	}

	function save_page($core, $args) {
		// debug("here");
		set_option("site_homepage", $_POST['site_homepage']);
		set_option("disable_seo", $_POST['disable_seo']);
		set_option("admin_theme", $_POST['admin_theme']);

		\Alerts::instance()->success("Setttings updated");
		redirect($this->link);
	}

	function delete_page($core, $args) {

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

?>
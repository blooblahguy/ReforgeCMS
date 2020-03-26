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
		global $options;
		$pages = new Post();
		$pages = $pages->find("post_type = 'pages'");

		$home_page = get_option("site_homepage");
		$disable_seo = get_option("disable_seo");
		$seo_seperator = get_option("seo_seperator");
		$seo_keywords = get_option("seo_keywords");
		$sitename = get_option("sitename");

		?>
		<div class="row content-middle padb2">
			<div class="os-min padr2">
				<h2 class="marg0">Settings</h2>
			</div>
		</div>

		<div class="section">
			<? 
			$choices = array_extract($pages, "id", "title");
			render_admin_field($home_page, array(
				"type" => "select",
				"label" => "Site Homepage",
				"name" => "site_homepage",
				"choices" => $choices,
			));

			
			
			?>
		</div>

		<div class="section">
			<h2>SEO Settings</h2>
			<?
			render_admin_field($disable_seo, array(
				"type" => "checkbox",
				"label" => "Discourage Search Engines",
				"name" => "disable_seo",
			));
			?>
			<div class="row g1">
				<?
				render_admin_field($sitename, array(
					"type" => "text",
					"label" => "Site Title",
					"name" => "sitename",
				));
				render_admin_field($seo_seperator, array(
					"type" => "text",
					"label" => "Title Tag Seperator",
					"default" => "|",
					"name" => "seo_seperator",
				));
				render_admin_field($seo_keywords, array(
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

	function save_page($core, $args) {
		// debug("here");
		set_option("seo_title", $_POST['seo_title']);
		set_option("site_homepage", $_POST['site_homepage']);
		set_option("disable_seo", $_POST['disable_seo']);
		set_option("seo_seperator", $_POST['seo_seperator']);
		set_option("seo_keywords", $_POST['seo_keywords']);

		RCF()->save_fields("settings", "0");

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
<?

class admin_page_THEMES extends RF_Admin_Page {
	function __construct() {
		global $core;

		$this->category = "Settings";
		$this->name = "themes";
		$this->label = "Theme";
		$this->label_plural = "Themes";
		$this->admin_menu = 95;
		$this->icon = "web";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		// Be sure to set up the parent
		parent::__construct();

		$this->scan_themes();

		$core->route("GET {$this->route}/rescan", function() {
			\Cache::instance()->clear("themes");
			redirect("/admin/themes");
		});

		$core->route("GET {$this->route}/activate/@theme_slug", "admin_page_THEMES->activate");
	}

	function activate($core, $args) {
		if ($this->can_save()) {
			$theme = $args['theme_slug'];

			set_option("active_theme", $theme);

			\Alerts::instance()->success("Theme '{$theme}' activated");
			redirect("/admin/themes");
		}
	}

	function scan_themes() {
		global $root;
		$themes = \Cache::instance()->get("themes");

		if ($themes) { return $themes; }
		$themes = array();

		$path = $root.'/rf_content/themes/'; // '.' for current
		foreach (new DirectoryIterator($path) as $file) {
			if ($file->isDot()) continue;

			if ($file->isDir()) {
				$folder = $file->getFilename();
				$ini = $path.$file."/theme.ini";
				$thumbnail = "/rf_content/themes/".$file."/theme.jpg";
				if (! file_exists($ini)) { continue; }
				if (! file_exists($root.$thumbnail)) { $thumbnail = "/rf_core/img/default_theme.jpg"; }

				$info = parse_ini_file($ini);
				$info['thumbnail'] = $thumbnail;

				$themes[$folder] = $info;				
			}
		}

		\Alerts::instance()->success("Themes scanned");
		\Cache::instance()->set("themes", $themes);

		return $themes;
	}

	function render_index() {
		$themes = $this->scan_themes();
		$active = get_option('active_theme');

		?>

		<div class="row content-middle padb2">
			<div class="os-min padr2">
				<h2 class="marg0">Themes</h2>
			</div>
			<div class="os padl2">
				<a href="/admin/themes/rescan" class="btn">Rescan Themes</a>
			</div>
		</div>

		<div class="row g2 themlist">
			<? foreach ($themes as $slug => $info) {
				$current = false;
				if ($slug == $active) { $current = true; }
				?>
				<div class="os-3 theme_card<? if ($current) {echo " active"; } ?>">
					<div class="thumbnail"><img src="<?= $info['thumbnail']; ?>" alt="<?= $info['name']; ?>"></div>
					<div class="footer row">
						<div class="pad1 os">
							<?= $info['name']; ?>
						</div>
						<div class="pad1 os">
							<? if (! $current) { ?>
								<a href="/admin/themes/activate/<?= $slug; ?>" class="btn">Activate</a>
							<? } ?>
						</div>
					</div>
				</div>
			<? } ?>
		</div>
	<? }

	function render_edit() {
		
	}

	function save_page() {

	}

	function delete_page() {

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

new admin_page_THEMES();

?>
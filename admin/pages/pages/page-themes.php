<?

class admin_page_THEMES extends RF_Admin_Page {
	private $cache = array();
	function __construct() {
		global $core;

		$this->category = "Settings";
		$this->name = "themes";
		$this->label = "Theme";
		$this->label_plural = "Themes";
		$this->admin_menu_parent = "settings";
		$this->admin_menu = 95;
		$this->icon = "web";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		$this->cache = new RF\Cache("THEMES");

		// Be sure to set up the parent
		parent::__construct();

		$this->scan_themes();

		$core->route("GET {$this->route}/rescan", function() {
			$this->cache->reset();
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
		$themes = $this->cache->get("themes");

		if ($themes) { return $themes; }
		$themes = array();

		$path = $root.'/content/themes/'; // '.' for current
		foreach (new DirectoryIterator($path) as $file) {
			if ($file->isDot()) continue;

			if ($file->isDir()) {
				$folder = $file->getFilename();
				$ini = $path.$file."/theme.ini";
				$thumbnail = "/content/themes/".$file."/theme.jpg";
				if (! file_exists($ini)) { continue; }
				if (! file_exists($root.$thumbnail)) { $thumbnail = "/core/img/default_theme.jpg"; }

				$info = parse_ini_file($ini);
				$info['thumbnail'] = $thumbnail;

				$themes[$folder] = $info;				
			}
		}

		\Alerts::instance()->success("Themes scanned");
		$this->cache->set("themes", $themes);

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
				<div class="os-3">
					<div class="section padb1 theme_card<? if ($current) {echo " active"; } ?>">
						<img src="<?= $info['thumbnail']; ?>" class="bg" alt="<?= $info['name']; ?>">
						<div class="footer pad1 row content-middle">
							<div class="os strong">
								<?= $info['name']; ?>
							</div>
							<div class="os-min">
								<? if (! $current) { ?>
									<a href="/admin/themes/activate/<?= $slug; ?>" class="btn">Activate</a>
								<? } else { ?>
									<button class="btn-primary" disabled>Active</button>
								<? } ?>
							</div>
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


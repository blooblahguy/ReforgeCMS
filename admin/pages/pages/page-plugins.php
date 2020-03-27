<?

class admin_page_PLUGINS extends RF_Admin_Page {
	private $cache = array();
	function __construct() {
		global $core, $plugin_cache; 

		$this->category = "Settings";
		$this->name = "plugins";
		$this->label = "Plugin";
		$this->label_plural = "Plugins";
		$this->admin_menu_parent = "settings";
		$this->admin_menu = 90;
		$this->icon = "settings_input_svideo";
		$this->base_permission = "manage_settings";
		$this->link = "/admin/{$this->name}";
		$this->disable_header = true;

		$this->cache = new RF\Cache("THEMES");

		// Rescan route
		$core->route("GET {$this->link}/rescan", function() {
			$this->cache->reset();
			redirect("/admin/plugins");
		});

		$core->route("GET {$this->link}/activate/@slug", "admin_page_PLUGINS->activate");
		$core->route("GET {$this->link}/deactivate/@slug", "admin_page_PLUGINS->deactivate");

		// Be sure to set up the parent
		parent::__construct();
	}

	function scan_plugins() {
		global $root;
		$plugins = $this->cache->get("plugins");

		if ($plugins) { return $plugins; }
		$plugins = array();

		$path = $root.'/content/plugins/'; // '.' for current
		foreach (new DirectoryIterator($path) as $file) {
			if ($file->isDot()) continue;

			if ($file->isDir()) {
				$folder = $file->getFilename();
				$ini = $path.$file."/plugin.ini";
				$thumbnail = "/content/plugins/".$file."/plugin.jpg";
				if (! file_exists($ini)) { continue; }
				if (! file_exists($root.$thumbnail)) { $thumbnail = "/core/img/default_theme.jpg"; }

				$info = parse_ini_file($ini);
				$info['thumbnail'] = $thumbnail;

				$plugins[$folder] = $info;				
			}
		}

		\Alerts::instance()->success("Plugins scanned");
		$this->cache->set("plugins", $plugins);

		return $plugins;
	}

	function render_index() {
		$plugins = $this->scan_plugins();
		$active_plugins = get_option("active_plugins");
		if (! $active_plugins) {
			$active_plugins = serialize(array());
		}
		$active_plugins = unserialize($active_plugins);

		?>
		<div class="row content-middle padb2">
			<div class="os-min padr2">
				<h2 class="marg0">Plugins</h2>
			</div>
			<div class="os padl2">
				<a href="/admin/plugins/rescan" class="btn">Rescan Plugins</a>
			</div>
		</div>
		

		<div class="row g2 themlist">
			<? foreach ($plugins as $slug => $info) {
				$current = false;
				if ($active_plugins[$slug]) { $current = true; }
				?>
				<div class="os-3">
					<div class="section padb1 theme_card<? if ($current) {echo " active"; } ?>">
						<img src="<?= $info['thumbnail']; ?>" class="bg" alt="<?= $info['name']; ?>">
						<div class="footer pad1 row content-middle">
							<div class="os strong h4 margb0">
								<?= $info['name']; ?>
							</div>
							<div class="os-min">
								<? if (! $current) { ?>
									<a href="/admin/plugins/activate/<?= $slug; ?>" class="btn">Activate</a>
								<? } else { ?>
									<a href="/admin/plugins/deactivate/<?= $slug; ?>" class="btn-primary">Deactivate</a>
								<? } ?>
							</div>
							<div class="os-12 plugin_info padt1">
								<div class="line">
									<?= $info['description']; ?>
								</div>
								<div class="line row content-space">
									<div class="os">Version <?= $info['version']; ?></div>
									<!-- <div class="os-min">|</div> -->
									<div class="os text-center">By <a href="<?= $info['authorURL']; ?>" target="_blank"><?= $info['author']; ?></a></div>
									<!-- <div class="os-min">|</div> -->
									<div class="os text-right"><a href="<?= $info['url']; ?>" target="_blank">Visit plugin site</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
		</div>
		<?
	}

	function render_edit() {
		
	}

	function activate($core, $args) {
		$slug = $args['slug'];
		if (! $slug) { return; }

		$active_plugins = get_option("active_plugins");
		if (! $active_plugins) {
			$active_plugins = serialize(array());
		}
		$active_plugins = unserialize($active_plugins);

		$active_plugins[$slug] = plugins_dir()."/$slug/$slug.php";

		set_option("active_plugins", serialize($active_plugins));
		\Alerts::instance()->success("Plugin '{$slug}' activated");
		redirect("/admin/plugins");
	}
	function deactivate($core, $args) {
		$slug = $args['slug'];
		if (! $slug) { return; }

		$active_plugins = get_option("active_plugins");
		if (! $active_plugins) {
			$active_plugins = serialize(array());
		}
		$active_plugins = unserialize($active_plugins);

		unset($active_plugins[$slug]);

		set_option("active_plugins", serialize($active_plugins));
		\Alerts::instance()->warning("Plugin '{$slug}' deactivated");
		redirect("/admin/plugins");
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

new admin_page_PLUGINS();


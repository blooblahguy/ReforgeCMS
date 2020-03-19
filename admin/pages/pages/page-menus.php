<?

class admin_page_MENUS extends RF_Admin_Page {
	function __construct() {
		$this->category = "Design";
		$this->name = "menus";
		$this->label = "Menu";
		$this->label_plural = "Menus";
		$this->admin_menu = 50;
		$this->icon = "menus";
		$this->base_permission = "manage_menus";
		$this->link = "/admin/{$this->name}";

		// Be sure to set up the parent
		parent::__construct();
	}

	function render_index($core, $args) {
		$menu = new Menu();
		$menu = $menu->query("SELECT * FROM menus order by `order` ASC");
		debug($menu);
		?>

		<?
	}

	function menu_link($label, $permalink, $options) { ?>
		<div class="pad1 border"></div>
	<? }

	function menu_link_template($menu) {
		?>
		<div class="section margb1">
			<div class="row g1 content-middle">
				<div class="os-min dragger">
					<i>drag_indicator</i>
				</div>
				<div class="os">
					<label for="">Label</label>
					<input type="text" name="menu[][label]" value="<?= $menu['label']; ?>">
				</div>
				<div class="os">
					<label for="">URL</label>
					<input type="text" name="menu[][url]" value="<?= $menu['url']; ?>">
				</div>
				<div class="os-1">
					<label for="">Tag</label>
					<input type="text" name="menu[][tag]" value="<?= $menu['tag']; ?>">
				</div>
				<div class="os-1">
					<label for="">Class</label>
					<input type="text" name="menu[][class]" value="<?= $menu['class']; ?>">
				</div>
				<div class="os-min text-center">
					<label for="">Open in New Tab</label>
					<input type="checkbox" name="menu[][target]" class="margy1" value="1">
				</div>
			</div>
			<input type="hidden" name="menu[][start_children]" value="1">
			<div class="row g1 children content-middle"></div>
			<input type="hidden" name="menu[][end_children]" value="1">
		</div>
		<?
	}

	function render_edit($core, $args) {
		$id = $args['id'];

		$pts = new PostType();
		$pts = $pts->query("SELECT * FROM {$pts->model_table} WHERE public = 1 ORDER BY `order` ASC");

		$posts = new Post();
		$posts = $posts->query("SELECT * FROM {$posts->model_table} ORDER BY post_type ASC, modified DESC");

		$menu = new Menu();
		if ($id > 0) {
			$menu->load("id = $id");
		}

		?>
		<div class="row g2">
			<div class="os-3">
				<div class="rf_menu_links section">

					<? foreach ($pts as $type) { ?>
						<div class="accordion_handle pad1 menu_header" data-accordion=".<?= $type['slug']; ?>">
							<?= $type['label_plural']; ?>
						</div>
						<div class="accordion collapsed <?= $type['slug']; ?> pad1">
							<?
							foreach ($posts as $post) {
								if ($post['post_type'] == $type['slug']) { ?>
									<div class="link_type row pad1 content-middle">
										<label for="" class="os"><?= $post['title']; ?></label>
										<div class="btn btn-sm os-min" data-replace="label,url" data-label="<?= $post['title']; ?>" data-url="<?= $post['permalink']; ?>" data-template=".menu_item" data-target=".rf_menu .links">Add</div>
									</div>
								<? }
							}
							?>
						</div>
					<? } ?>

					<template class="menu_item">
						<? $this->menu_link_template(array(
							"label" => "\$label",
							"url" => "\$url",
						)); ?>
					</template>
				</div>
			</div>
			<div class="os">
				<div class="rf_menu section">
					<?
					render_admin_field($menu, array(
						"type" => "text",
						"label" => "Label",
						"name" => "label",
						"required" => true,
					));
					$links = $menu->get_menu_array();
					?>

					<div class="links margt2">
						<? foreach ($links as $l) {
							$this->menu_link_template($l);
						} ?>
					</div>
				</div>
			</div>
			<div class="os-2">
				<div class="section sidebar">
					<input type="submit">
				</div>
			</div>
		</div>


		<?
	}

	function find_menu_post($start) {
		$entry = array();

		$entry['label'] = reset($_POST['menu'][$start]);
		$entry['url'] = reset($_POST['menu'][$start + 1]);
		$entry['tag'] = reset($_POST['menu'][$start + 2]);
		$entry['class'] = reset($_POST['menu'][$start + 3]);
		$entry['children'] = $this->find_children($start + 5);

		return $entry;
	}

	function find_children($start) {
		$menu = array();

		for ($i = $start; $i <= count($_POST['menu']); $i++) {
			$info = $_POST['menu'][$i];
			$key = reset(array_keys($info));

			if ($key == "label") {
				$entry = $this->find_menu_post($i);
				$menu[] = $entry;
			} elseif ($key == "start_children") {
				$menu = $this->find_children($i + 1);
			} elseif ($key == "end_children") {
				break;
			}
		}

		return $menu;
	}

	function build_menu($start) {
		$menu = array();

		for ($i = $start; $i < count($_POST['menu']); $i++) {
			$info = $_POST['menu'][$i];
			$key = reset(array_keys($info));

			if ($key == "label") {
				$entry = $this->find_menu_post($i);
				$menu[] = $entry;
			}
		}

		return $menu;
	}

	function save_page($core, $args) {
		$id = $this->id;

		$menu = new Menu();
		$changed = "created";
		if ($id > 0) {
			var_dump($id);
			$changed = "updated";
			$menu->load("id = $id");
		}

		$links = $this->build_menu(0);
		$menu->label = $_POST['label'];
		$menu->links = serialize($links);
		$menu->save();

		\Alerts::instance()->success("Menu $menu->label $changed");
		redirect("{$this->link}/edit/{$menu->id}");
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

new admin_page_MENUS();

?>
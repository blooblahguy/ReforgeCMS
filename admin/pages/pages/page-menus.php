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
				<div class="rf_menu_links border">

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
										<div class="btn btn-sm os-min" data-replace="label,permalink" data-label="<?= $post['title']; ?>" data-permalink="<?= $post['permalink']; ?>" data-template=".menu_item" data-target=".rf_menu .links">Add</div>
									</div>
								<? }
							}
							?>
						</div>
					<? } ?>

					<template class="menu_item">
						$label
						<br>
						<input type="text">
						$permalink
					</template>
				</div>
			</div>
			<div class="os">
				<div class="rf_menu">
					<label for="label">Menu Name</label>
					<input type="text" name="label" value="<?= $menu->label; ?>" placeholder="Menu name...">

					<div class="links padt2">
						link
					</div>
				</div>
			</div>
		</div>


		<?
	}

	function save_page($core, $args) {

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
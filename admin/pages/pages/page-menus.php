<?php

class admin_page_MENUS extends RF_Admin_Page {
	function __construct() {
		$this->name = "menus";
		$this->label = "Menu";
		$this->label_plural = "Menus";
		$this->admin_menu = 50;
		$this->icon = "menus";
		$this->permissions = array(
			"all" => "manage_menus"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index($args) {
		$menu = new Menu();
		$menu = $menu->find("*", null, array(
			"order by" => "`order` ASC"
		));

		echo '<div class="section">';
		display_results_table($menu, array(
			"label" => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
		));
		echo '</div>';
	}

	function menu_link($label, $permalink, $options) { ?>
		<div class="pad1 border"></div>
	<? }

	function menu_link_template($menu) {
		?>
		<div class="menu_item pad1 bg-background margb1 sort border">
			<div class="row g1">
				<div class="os-min bg-darken dragger">
					<i>drag_indicator</i>
				</div>
				<div class="os">
					<div class="row g1 content-middle">
						<div class="os-3">
							<label for="">Label</label>
							<input type="text" name="menu[][label]" value="<?= $menu['label']; ?>">
						</div>
						<div class="os-3">
							<label for="">URL</label>
							<input type="text" name="menu[][url]" value="<?= $menu['url']; ?>">
						</div>
						<div class="os">
							<label for="">Tag</label>
							<input type="text" name="menu[][tag]" value="<?= $menu['tag']; ?>">
						</div>
						<div class="os">
							<label for="">Class</label>
							<input type="text" name="menu[][class]" value="<?= $menu['class']; ?>">
						</div>
						<div class="os-min text-center">
							<label for="">Open in New Tab</label>
							<input type="checkbox" name="menu[][target]" class="margy1" value="1" <? if ($menu['target']) {echo "checked"; }?>>
						</div>
					</div>
					<input type="hidden" name="menu[][start_children]" value="1">
					<div class="os children content-middle menu_group menu_children margt1" data-placeholder="Children"><? 
						$menu['children'] = isset($menu['children']) ? $menu['children'] : array();
						foreach ($menu['children'] as $child) {
							$this->menu_link_template($child); 
						} 
					?></div>
					<input type="hidden" name="menu[][end_children]" value="1">
				</div>
				<div class="os-min bg-darken">
					<div class="row h100 content-middle self-middle ">
						<div class="os btn_remove" data-remove=".menu_item"><i>remove_circle</i></div>
					</div>
				</div>
			</div>
			
		</div>
		
		<?
	}

	function edit($args) {
		$id = $args['id'];

		$pts = new PostType();
		$pts = $pts->query("SELECT * FROM {$pts->table} WHERE public = 1 ORDER BY `order` ASC");

		$posts = new Post();
		$posts = $posts->query("SELECT * FROM {$posts->table} ORDER BY post_type ASC, modified DESC");

		$menu = new Menu();
		if ($id > 0) {
			$menu->load("*", array("id = :id", ":id" => $id));
		}

		?>
		<div class="row g2">
			<div class="os-3">
				<div class="autosticky rf_menu_links section">

					<? 
					$notfirst = false;
					foreach ($pts as $type) {
						
						?>
						<div class="accordion_handle pad1 menu_header <? if (! $notfirst) {echo "toggled"; } ?>" data-accordion=".<?= $type['slug']; ?>">
							<?= $type['label_plural']; ?>
						</div>
						<div class="accordion <? if ($notfirst) {echo "collapsed"; } ?> <?= $type['slug']; ?> pad1">
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
						<? 
						$notfirst = true;
					} ?>

					<template class="template menu_item">
						<? $this->menu_link_template(array(
							"label" => "\$label",
							"url" => "\$url",
							"children" => array(),
						)); ?>
					</template>
				</div>
			</div>
			<div class="os">
				<div class="rf_menu section">
					<?
					render_html_field($menu, array(
						"type" => "text",
						"label" => "Label",
						"name" => "label",
						"class" => "post_title",
						"required" => true,
					));
				
					$links = $menu->get_menu_array();
					?>

					<div class="links margt2 menu_group" data-sort-group="menu">
						<? foreach ($links as $l) {
							$this->menu_link_template($l);
						} ?>
					</div>
					<div class="btn pull-right" data-replace="label,url" data-label="" data-url="" data-template=".menu_item" data-target=".rf_menu .links"><i>add_circle</i> Custom Link</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="os-2">
				<div class="section sidebar autosticky">
					<input type="submit">

					<?
					render_html_field($menu, array(
						"type" => "text",
						"name" => "slug",
						"label" => "Slug",
						"layout" => "vertical",
						"class" => "post_permalink",
						"required" => true,
					));
					?>
				</div>
			</div>
		</div>


		<?
	}

	function find_menu_post($start = 0) {
		// debug("start at $start");
		$menu = array();
		$entry = array();
		for ($i = $start; $i < 100; $i++) {
			$current = $_POST['menu'][$i];
			if (! isset($current)) { break; }

			$key = reset(array_keys($current));
			$value = reset($current);

			if ($key == "start_children") {
				unset($_POST['menu'][$i]);
				$entry['children'] = $this->find_children($i + 1);
				break;
			}
			if ($key == "end_children") {
				unset($_POST['menu'][$i]);
				debug("end children found $i");
				break;
			}

			unset($_POST['menu'][$i]);
			$entry[$key] = $value;
		}
		if ($entry['label'] == "") { return null; }
		$menu[] = $entry;

		// continue if theres more keys
		$current = current(array_keys($_POST['menu']));
		// $next = $_POST['menu'][$current + 1];
		// if (isset($next)) {
		// 	$next = reset(array_keys($next));
		// 	if ($next == "end_children") {
		// 		unset($_POST['menu'][$current + 1]);
		// 	}
		// }
		
		$next = $current + 1;

		if (isset($_POST["menu"][$next])) {
			$entries = $this->find_menu_post($current);
			if ($entries) {
				$menu = array_merge ($menu, $entries);
			}
		}

		return $menu;
	}

	function find_children($start) {
		$menu = array();

		for ($i = $start; $i < 100; $i++) {
			$current = $_POST['menu'][$i];
			if (! isset($current) ) { break; }
			
			$key = reset(array_keys($current));
			$value = reset($current);

			if ($key == "start_children") {
				unset($_POST['menu'][$i]);
				$menu['children'] = $this->find_children($i + 1);
				break;
			}
			if ($key == "end_children") {
				unset($_POST['menu'][$i]);
				break;
			}


			$entries = $this->find_menu_post($i);
			if ($entries) {
				$menu = array_merge ($menu, $entries);
			}
		}


		return $menu;
	}

	function build_menu() {
		if (! isset($_POST['menu'])) { return array(); }
		$menu = array();

		$menu = $this->find_menu_post();

		return $menu;
	}

	function save($args) {
		$id = $this->id;

		$menu = new Menu();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$menu->load("*", array("id = :id", ":id" => $id));
		}

		$links = $this->build_menu();

		$menu->label = $_POST['label'];
		$menu->slug = $_POST['slug'];
		$menu->links = serialize($links);
		$menu->save();

		\Alerts::instance()->success("Menu $menu->label $changed");
		redirect("{$this->link}/edit/{$menu->id}");
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

new admin_page_MENUS();


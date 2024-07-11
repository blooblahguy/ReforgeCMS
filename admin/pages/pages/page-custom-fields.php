<?php

class admin_page_CUSTOMFIELDS extends RF_Admin_Page {
	

	function __construct() {
		$this->name = "custom_fields";
		$this->label = "Custom Field";
		$this->label_plural = "Custom Fields";
		$this->admin_menu_parent = "settings";
		$this->admin_menu = 75;
		$this->icon = "filter_list";
		$this->permissions = array(
			"all" => "manage_post_types"
		);

		// Be sure to set up the parent
		parent::__construct();
	}

	function index( $args ) {
		$this->render_title();

		$cf = new CustomField();
		$fieldsets = $cf->find( "*", "virtual = 0" );

		echo '<div class="section">';
		display_results_table( $fieldsets, array(
			"title" => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="/admin/custom_fields/edit/%2$d">%1$s</a>',
			),
			'remove' => array(
				"label" => "Remove",
				"class" => "min",
				"calculate" => function ($s, $r) {
					return "<a href='{$this->link}/delete/{$r['id']}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		) );
		echo '</div>';
	}

	function edit( $args ) {
		$this->render_title();

		$id = $this->id;
		$cf = new CustomField();
		$action = "Create";
		$subject = "Custom Fields";
		$fields = array();
		$load_rules = array();
		if ( $id > 0 ) {
			$cf->load( "*", array( "id = :id", ":id" => $id ), null, 1 );
			$action = "Edit";
			$subject = ucfirst( $cf->title );
			$fields = unserialize( $cf->fieldset );
			$load_rules = unserialize( $cf->load_rules );
		}
		?>

		<div class="section">
			<label for="title">Title</label>
			<div class="row cfheader">
				<div class="os padr2">
					<input type="text" name="title" value="<?= $cf->title; ?>" placeholder="Title">
				</div>
				<div class="os-2">
					<input type="submit" class="marg0 btn-primary w100" value="Save">
				</div>
			</div>
		</div>

		<div class="section">
			<h2>Fields</h2>
			<?
			// Renders the fields
			do_action( "rcf/admin_render_settings", $id );
			?>
		</div>

		<div class="section">
			<h2>Load Conditions</h2>
			<?
			// render the rules
			do_action( "rcf/admin_render_rules", $id );
			?>
		</div>
		<div class="section">
			<h2>Options</h2>
			<?
			render_html_field( $cf, array(
				"type" => "checkbox",
				"label" => "Active",
				"name" => "inactive",
				"value" => "1",
			) );
			render_html_field( $cf, array(
				"type" => "select",
				"label" => "Display Priority",
				"name" => "priority",
				"choices" => array(
					3 => "Very High",
					2 => "High",
					0 => "Normal",
					-1 => "Low",
					-2 => "Very Low"
				),
			) );
			?>
		</div>
	<?
	}



	function save( $args ) {
		$id = $this->id;
		$cf = new CustomField();
		$changed = "created";
		if ( $id > 0 ) {
			$changed = "updated";
			$cf->load( "*", array( "id = :id", ":id" => $id ) );
		}

		$title = $_POST["title"];
		$cf->save_fieldset();

		\Alerts::instance()->success( "Custom Field $title $changed" );
		redirect( "/admin/custom_fields/edit/{$cf->id}" );

	}

	function delete( $args ) {
		$cf = new CustomField();
		$cf->id = $args['id'];

		$cf->erase();

		\Alerts::instance()->success( "Custom Field deleted" );
		redirect( $this->link );
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

$custom_fields_page = new admin_page_CUSTOMFIELDS();


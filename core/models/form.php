<?php
class Form extends \RF\Mapper {
	function __construct() {
		parent::__construct("rf_posts", false);
	}

	function render() {
		global $request;
		if (! $this->id) { return; }

		$cf = new CustomField();
		$field = reset($cf->find(array("id = :id", ":id" => $this->post_parent)));

		// debug($field);

		// debug($this);
		// debug($cf);
		// debug($this->post_parent);

		?>
		<div class="rf_form">
			<form method="POST" action="/rf_form/process/<?= $this->id; ?>">
				<h3 class="form_title"><?= $this->title; ?></h3>
				<div class="form_instructions"><?= $this->subtitle; ?></div>

				<?
				RCF()->render_fields($field['id'], $request['page_id'], "form", $field);
				?>

				<input type="submit">
			</form>
		</div>
		<?
	}
}

// Load form shortocdes
$posts = new Post();
$forms = $posts->find("post_type = 'forms'");
add_shortcode("form", function($attrs) {
	$form = new Form();
	$form->load(array("slug = :slug AND post_type = 'forms' ", ":slug" => $attrs['slug']));

	// debug($form);

	$form->render();
});


$core->route("POST /rf_form/process/@id", function($core, $args) {
	debug($args);
	debug($_POST);

	exit();
});
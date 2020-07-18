<?php

?>
<div class="post">
	<div class="post_header padx1 bg-dark">
		<div class="row g1 content-middle">
			<div class="os-min padx2">
				<div class="avatar_icon">
					<img src="<?= get_user($app['author'])->avatar; ?>" alt="">
				</div>
			</div>
			<div class="os">
				<div class="post_title strong">
					<?= the_title($app['id']); ?>
				</div>
				<div class="post_info">
					<span class="user"><?= the_author($app['id']); ?></span>
					<span class="date small muted">on <?= the_date($app['id']); ?></span>
				</div>
			</div>
		</div>
		<div class="user_info row g1">
			
		</div>
	</div>
	<div class="post_content bg-black pad2 pady1 application_view">
		<?

		// $current_data = RCF()->load_fields("application", $app['id']);
		$fieldset = RCF()->get_fields("application", $app['id']);
		// debug($fieldset);
		// debug($current_data);
		// $fields = get_fields("application_".$app['id']);

		// $form = new Form();
		// $form = $form->load("*", array("id = :id", ":id" => $app->post_parent));

		// $cf = new CustomField();
		// $field = reset($cf->find("*", array("id = :id", ":id" => $form->post_parent)));
		// $fieldset = unserialize($field['fieldset']);
		// $field_info = array();
		// foreach ($fieldset as $f) {
		// 	$field_info[$f['slug']] = array(
		// 		'slug' => $f['slug'],
		// 		'label' => $f['label'],
		// 		'type' => $f['type'],
		// 	)
		// 	// debug($f);
		// }

		// // debug($fieldset);

		// foreach ($fields as $secion => $questions) {

		// }
		// debug($fields);
		render_entry_results($app['id']);
		?>
	</div>
</div>

<?
	post_comments($app['id']);
?>

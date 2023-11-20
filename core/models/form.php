<?php


class Form extends \RF\Mapper {
	function __construct() {
		parent::__construct("rf_posts", false);
		$this->post_type = "form";
	}

	function render($args) {
		global $request, $core;
		if (! $this->id) { return; }

		$cf = new CustomField();
		$field = reset($cf->find("*", array("id = :id", ":id" => $this->post_parent)));
		$entry = $request['page_id'];
		$type = "form";
		if ($args['entry']) {
			$entry = $args['entry'];
		}
		if ($args['type']) {
			$type = $args['type'];
		}

		$label = "Submit";
		if (! $args['hide_title']) {
			$label = $this->title;
		}

		?>
		<div class="rf_form">
			<script>
				function disableButton(form) {
					var btn = $(form).find(".form_submit")
					btn.disabled = true
				}
			</script>
			<form id="recaptcha-form" onsubmit="disableButton(this)" method="POST" onkeydown="return event.key != 'Enter';" <?if ($args['target']) {echo "target='_blank' "; }?> action="/rf_form/process/<?= $this->id; ?>">
				<? if (! $args['hide_title']) { ?>
					<h3 class="form_title"><?= $this->title; ?></h3>
				<? } ?>
				<div class="form_instructions"><?= $this->subtitle; ?></div>

				<?
				RCF()->render_fields($field['id'], $entry, $type, $field);
				?>

				<input type="hidden" name="redirect" value="<? echo $core->SERVER['REDIRECT_URL']; ?>">

				<? add_recaptcha($label); ?>
			</form>
		</div>
		<?
	}

	function submit($entry_id = 0, $type = "form_entry") {
		if (! $this->id) {
			return;
		}

		$captcha = new \Web\Google\Recaptcha();
		if (! $captcha->verify("6LdLF_8UAAAAAMFZM_8K_7x1KAIVwo1VGvJ7acXO")) {
			Alerts::instance()->error("Invalid recaptcha");
			redirect();
		}

		$type = apply_filters("form/submit/type", $type, $this->id);

		$entry = new Post();
		if ($entry_id != 0) {
			$entry->load("*", array("id = :id", ":id" => $entry_id));
		}
		$entry->post_type = $type;
		$entry->post_parent = $this->id;
		$entry->author = current_user()->id;
		$entry->seo_enable = 1;
		$entry->title = $this->title." - ".current_user()->username;

		$entry = apply_filters("form/submit", $entry, $entry_id);

		$entry->save();
		RCF()->save_fields($type, $entry->id);

		Alerts::instance()->success("Successfully submitted form");

		$redirect = apply_filters("form/redirect", $_POST['redirect'], $this->id, $entry->id);


		redirect($redirect);
	}
}
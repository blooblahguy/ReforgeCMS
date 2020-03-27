<?

class reforge_field_IMAGE extends reforge_field {
	// Registration
	function __construct() {
		$this->name = "image";
		$this->label = "Image";
		$this->category = "Content";
		$this->defaults = array();

		// now register in parent
		parent::__construct();
	}

	//========================================================
	// EDIT
	//========================================================
	function html($data, $field) {
		$key = $data['name'];

		// debug($data);
		// debug($field);

		// debug($value);
		// $image = "";
		// if (isset($value) && $value) {
			// $file = new RF_File();
			// $file->load("id = $value");
			// $image = $file->original;
		// }

		?>
		<div class="fieldset">
			<div class="row content-middle">
				<div class="os-12 field_label">
					<label for="title"><?= $field['label']; ?></label>
				</div>
				<div class="os">
					<? 
					$id = (int) $data['meta_value'];
					$file = new RF_File();
					if ($id > 0) {
						$file->load("id = $id");
					}
					$src = "style='display:none'";
					$rm_btn = "style='display:none'";
					$datasrc = "";
					if ($id != 0) {
						$src = "src='/core/img/image_placeholder.jpg'";
						$datasrc = "data-src='{$file->original}'";
						$class = "lazy";
						$rm_btn = "";
					} ?>
					<div class="preview">
						<img class="image_preview preview_<?= $key; ?> <?= $class; ?>" <?= $src; ?> <?= $datasrc; ?> alt="<?= $field['label']; ?>">
						<a href="#0" data-key="<?= $key; ?>" class="rf_media_remove" <?= $rm_btn; ?>><i>close</i></a>
					</div>
					<?= RF_Media::instance()->select_button($key);?>
					<input type="hidden" name="<?= $key; ?>" value="<?= $id; ?>">
				</div>
			</div>
		</div>
		<?

		echo '';
	}


	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($field) {

		// Layout
		rcf_render_field_setting($field, array(
			"label" => "Layout",
			"type" => "select",
			"name" => "layout",
			"choices" => array(
				"os-12" => "Full",
				"os" => "Auto Fit",
				"os-min" => "Minimum",
				"os-9" => "3/4",
				"os-8" => "2/3",
				"os-6" => "1/2",
				"os-4" => "1/3",
				"os-3" => "1/4",
			)
		));
	}
}


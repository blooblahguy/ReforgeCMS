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
		$value = $data['meta_value'];
		$image = "";
		if (isset($value) && $value) {
			$file = new RF_File();
			$file->load("id = $value");
			$image = $file->original;
		}

		?>
		<div class="fieldset">
			<div class="row content-middle">
				<div class="os-12 field_label">
					<label for="title"><?= $field['label']; ?></label>
				</div>
				<div class="os">
					<? 
					$src = "style='display:none'";
					$datasrc = "";
					if ($image != "") {
						$src = "src='/core/img/image_placeholder.jpg'";
						$datasrc = "data-src='$image'";
						$class = "lazy";
					} ?>
					<div class="preview">
						<img class="image_preview preview_<?= $key; ?> <?= $class; ?>" <?= $src; ?> <?= $datasrc; ?> alt="<?= $field['label']; ?>">
					</div>
					<?= RF_Media::instance()->select_button($key);?>
					<input type="hidden" name="<?= $key; ?>" value="<?= $value; ?>">
					<input type="hidden" name="<?= $key; ?>_path" value="<?= $value; ?>">

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

?>
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
		RF_Media::instance()->select_button();
		// render_admin_field($data, array(
		// 	"type" => "file",
		// 	"label" => $field['label'],
		// 	"name" => $data["name"],
		// 	"required" => $field['required'],
		// 	"placeholder" => $field['placeholder'],
		// ));
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
				"os-9" => "3/4",
				"os-8" => "2/3",
				"os-6" => "1/2",
				"os-4" => "1/3",
				"os-3" => "1/4"
			)
		));
	}

	function prepare_save($meta, $metas) {

		// get file array out of complexified file upload
		$key = $meta['meta_key'];
		$file = array();
		foreach ($_FILES['rcf_meta'] as $k => $info) {
			$file[$k] = $info[$key]['meta_value'];
		}

		$media = Media::instance();
		$media->upload_image($file);

		exit();
	}
}

?>
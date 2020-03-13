<?php

class RF_Media extends Prefab {
	const MODE = 0755;
	public $sizes = array();
	public $compression = 9;

	private $files = array("pdf");
	private $images = array("png", "jpg", "jpeg", "svg");

	// Double check permissions before routes
	function beforeroute() {
		$user = current_user();
		if (! $user->can("upload_files")) {
			Alerts::instance()->error("You don't have permission to do that.");
			redirect("/admin");
		}
	}

	/**
	 * Create Directories that don't exist
	 */
	function ensure_directory($path) {
		if (! is_dir($path)) {
			mkdir($path, $this::MODE, true);
		}
	}

	/**
	 * Add image size to process
	 */
	function add_size($name, $width, $height = null, $crop = true, $enlarge = false) {
		$this->sizes[$name] = array(
			"name" => $name, 
			"width" => $width, 
			"height" => $height,
			"crop" => $crop,
			"enlarge" => $enlarge,
		);

		$this->sizes = apply_filters("image_sizes", $this->sizes);
	}

	/**
	 * Setup routes
	 */
	function __construct() {
		global $core;

		queue_style("/core/reforge/media/media.css");

		// ROUTES
		$core->route("GET /admin/rf_media/load_media", "RF_Media->load_media");
		$core->route("GET /admin/rf_media/display", "RF_Media->display");
		// $core->route("GET /admin/rf_media/delete", "RF_Media->delete");

		// POST ROUTES
		$core->route("POST /admin/rf_media/upload", "RF_Media->upload");
		// $core->route("POST /admin/rf_media/save", "RF_Media->save");
		$core->route("POST /admin/rf_media/view", "RF_Media->view");
	}

	/**
	 * Hash image contents for unique id
	 */
	function file_hash($path) {
		return hash_file("md5", $path);
	}

	/**
	 * Upload single file & Create sizes for images
	 */
	function upload($core, $args) {
		$file = $_FILES['file'];
		$hash = $this->file_hash($file['tmp_name']);
		$tmp = $file['tmp_name'];
		
		// try and load existing file from database
		$file = new RF_File();
		$file->load("hash = '{$hash}'");
		// if ($file->name) {
		// 	echo "Duplicate file";
		// 	$core->status(200);
		// 	return;
		// }

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$name = substr($file['name'], 0, -strlen($ext)-1);

		$file->name = $name;
		$file->extension = $name;
		$file->hash = $hash;
		$file->images = array();

		// Image
		if (in_array($ext, $this->images)) {
			$raw = "{$name}.raw.{$ext}";
			move_uploaded_file($tmp, uploads_dir().$raw);

			foreach ($this->sizes as $size) {

			}
		} elseif (in_array($ext, $this->files)) {

		} else {
			echo "File ".$name." is invalid type";
			$core->status(200);
			return;
		}
	}

	function save() {

	}

	/**
	 * Display gallery widget
	 */
	function display() {
		?>
			<div class="rf_media">
				<form id="dropper" class="dropper text-center bg-light-grey pad4">
					<em class="muted">Drag Files to Upload</em>
					<input type="file" class="dropper_field" multiple accept="image/*" onchange="handleFiles(this.files)">
					<div class="clear"></div>
					<progress id="dropper_progress" class="progress" max=100 value=0></progress>
					<div id="preview"></div>
				</form>
				<div class="gallery">

				</div>
			</div>
			<script src="/core/reforge/media/media.js"></script>
		<?
	}
}


/**
 * Global functions
 */
function uploads_dir() {
	global $root;
	return $root."/content/uploads/";
}

function uploads_url() {
	return "/content/uploads/";
}

?>
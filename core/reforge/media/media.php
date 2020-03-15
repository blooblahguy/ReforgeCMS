<?php

class RF_Media extends Prefab {
	const MODE = 0755;
	public $sizes = array();
	public $compression = 9;
	public $path = "/content/uploads/";

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
		global $core, $root;

		queue_style("/core/reforge/media/media.css");

		// ROUTES
		$core->route("GET /admin/rf_media/load_media", "RF_Media->load_media");
		$core->route("GET /admin/rf_media/display", "RF_Media->display");
		$core->route("GET /admin/rf_media/edit/@id", "RF_Media->edit");
		// $core->route("GET /admin/rf_media/delete", "RF_Media->delete");

		// POST ROUTES
		$core->route("POST /admin/rf_media/upload", "RF_Media->upload");
		// $core->route("POST /admin/rf_media/save", "RF_Media->save");
		$core->route("POST /admin/rf_media/view", "RF_Media->view");

		// Directories
		$this->ensure_directory($root.$this->path);
		$this->ensure_directory($root.$this->path."sizes");
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

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$name = str_replace('.raw.', ".", $file['name']);
		$name = str_replace('.raw.', ".", $name);
		$name = str_replace('.raw.', ".", $name);
		$name = str_replace('.raw.', ".", $name);
		$name = str_replace('.raw.', ".", $name);
		foreach ($this->sizes as $size) {
			$name = str_replace(".{$size['name']}.", ".", $name);
		}
		$name = str_replace(".$ext", "", $name);
		
		// try and load existing file from database
		$file = new RF_File();
		$file->load("hash = '{$hash}'");
		$file->name = $name;
		$file->extension = $ext;
		$file->hash = $hash;

		if ($file->original) {
			return;
		}

		// Image
		if (in_array($ext, $this->images)) {
			$raw = "{$name}.raw.{$ext}";
			$file->original = uploads_url().$raw;
			$file->type = "image";
			move_uploaded_file($tmp, uploads_dir().$raw);
		} elseif (in_array($ext, $this->files)) {
			$raw = "{$name}.{$ext}";
			$file->original = uploads_url().$raw;
			$file->type = "file";
			move_uploaded_file($tmp, uploads_dir().$raw);
		} else {
			echo "File ".$name." is invalid type";
			$core->status(200);
			return;
		}

		$file->save();
	}

	//=============================================================
	// Views
	//=============================================================
	function edit($id) {
		$file = new RF_File();
		$file->load("id = $id");
		?>
			<div class="padx2 rf_media_edit">
				<div class="image text-center">
					<img src="<?= $file->original; ?>" alt="<?= $file->name; ?>">
					<strong class="name pad1"><?= $file->name.".".$file->extension; ?></strong>
				</div>

				<form class="details" method="POST" action="/admin/rf_media/save/<?= $id; ?>">
					<input type="text" disabled value="<?= $file->original; ?>">
					<div class="formsec">
						<label for="">Title</label>
						<input type="text" name="name" value="<?= $file->name; ?>">
					</div>

					<input type="submit" value="Save">

					<br><br>

					<a href="/admin/rf_media/regenerate/<?= $id; ?>" class="regenerate btn display-block">Regenerate</a>
					<a href="/admin/rf_media/regenerate/<?= $id; ?>" class="regenerate btn display-block">Delete</a>

				</form>
			</div>
		<?
	}

	function save() {

	}

	// resize file code
	// $img = new Image($raw, false, $root);
	// 		$img_name = "{$name}.{$size['name']}.{$ext}";
	// 		$img->resize($size['width'], $size['height'], $size['crop'], $size['enlarge']);

	// 		$path = $entry->upload($img, $img_name, $ext);
	// 		$images[$size['name']] = $path;

	function get_uploads() {
		global $db;
		$media = $db->exec("SELECT * FROM rf_media ORDER BY modified DESC, created DESC");

		return $media;
	}

	/**
	 * Display gallery widget
	 */
	function display($size = "2") {
		?>
			<div class="rf_media">
				<div id="dropper" class="dropper text-center pad4">
					<em class="muted">Drag Files to Upload</em>
					<input type="file" class="dropper_field" multiple accept="image/*" onchange="handleFiles(this.files)">
					<div class="clear"></div>
					<progress id="dropper_progress" class="progress margt2" max=100 value=0></progress>
					<div id="preview"></div>
				</div>
				<div class="rf_gallery" id="rf_gallery">
					<div class="row g1 padr1">
						<?
						$uploads = $this->get_uploads();
						foreach ($uploads as $file) { 
							$bg = $file['original'];
							if ($file['type'] == "file") {
								$bg = "/core/img/".$file['extension']."_default.png";
							}
						?>
						<div class="os-lg-<?= $size; ?> os-md-3 os-sm-4">
							<div class="file_card square">
								<img src="/core/img/image_placeholder.jpg" data-src="<?= $bg; ?>" alt="<?= $file['name']; ?>" class="bg lazy">
								<a href="/admin/media/edit/<?= $file['id']; ?>" data-id="<?= $file['id']; ?>" class="overlay"></a>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
			<script src="/core/reforge/media/media.js"></script>
			<script src="/core/reforge/media/lazy.js"></script>
			<script>
				var lazyLoadInstance = new LazyLoad({
					elements_selector: ".lazy"
				});
			</script>
		<?
	}
}


/**
 * Global functions
 */
function uploads_dir() {
	global $root;
	return $root.RF_Media::instance()->path;
}

function uploads_url() {
	return RF_Media::instance()->path;
}

?>
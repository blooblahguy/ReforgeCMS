<?php

class Media extends Prefab {
	const MODE = 0755;
	public $sizes = array();
	public $compression = 9;
	public $path = "/content/uploads/";

	private $files = array("pdf");
	private $images = array("png", "jpg", "svg");

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

		$this->add_size("medium", 400, null, false);
		$this->add_size("large", 1000, null, false);
		$this->add_size("hero", 1600, 400);

		$this->sizes = apply_filters("admin/media_sizes", $this->sizes);

		queue_style("/core/reforge/assets/media.css");
		queue_script("/core/reforge/assets/media.js");

		// ROUTES
		$core->route("GET /admin/rf_media/load_media", "Media->load_media");
		$core->route("GET /admin/rf_media/display", "Media->display");
		$core->route("GET /admin/rf_media/select", "Media->select");
		$core->route("GET /admin/rf_media/edit/@id", "Media->edit");
		$core->route("GET /admin/rf_media/delete/@id", "Media->delete");
		$core->route("GET /admin/rf_media/regenerate/@id", "Media->regenerate");

		// POST ROUTES
		$core->route("POST /admin/rf_media/upload", "Media->upload");
		// $core->route("POST /admin/rf_media/save", "Media->save");
		$core->route("POST /admin/rf_media/view", "Media->view");

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
	function save_size($size_key, $file) {
		global $root, $core;
		// Get sanitized name
		$path = $file;
		$file = basename($file);
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$name = str_replace(".raw.", ".", $file);
		foreach ($this->sizes as $size) {
			$name = str_replace(".{$size['name']}.", ".", $name);
		}
		$name = str_replace(".$ext", "", $name);

		// Now resize image
		$size = $this->sizes[$size_key];
		$img = new Image($path, false, $root);

		// debug($img);
		// debug($img->height);
		// debug($img->render);
		$img_name = "{$name}.{$size_key}.{$ext}";
		$img->resize($size['width'], $size['height'], $size['crop'], $size['enlarge']);

		$compression = $this->compression;
		if ($ext == "jpg") { 
			$ext = "jpeg";
			$compression = ($compression + 1) * 10;
		}

		$core->write(uploads_dir()."sizes/$img_name", $img->dump($ext, $compression));

		return uploads_url()."sizes/$img_name";
	}

	/**
	 * Upload single file
	 */
	function upload($core, $args) {
		$file = $_FILES['file'];
		$hash = $this->file_hash($file['tmp_name']);
		$tmp = $file['tmp_name'];

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$name = str_replace('.raw.', ".", $file['name']);
		foreach ($this->sizes as $size) {
			$name = str_replace(".{$size['name']}.", ".", $name);
		}
		$name = str_replace(".$ext", "", $name);
		
		// try and load existing file from database
		$file = new File();
		$file->load("*", array("hash = :hash", ":hash" => $hash));
		$file->name = $name;
		$file->extension = $ext;
		$file->hash = $hash;

		if ($file->original) {
			return $file;
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

		return $file;
	}

	//=============================================================
	// Views
	//=============================================================
	function delete($core, $args) {
		$id = $args['id'];

		if (current_user()->can("manage_media")) {
			$file = new File();
			$file->load("*", array("id = :id", ":id" => $id));

			$file->erase();
		}

		redirect("/admin/media");
	}

	function regenerate($core, $args) {
		$id = $args['id'];

		if (current_user()->can("manage_media")) {
			$file = new File();
			$file->load("id, sizes", array("id = :id", ":id" => $id));

			$file->sizes = serialize(array());
			$file->update();
		}

		redirect("/admin/media");
	}

	function edit($core, $args) {
		$id = $args['id'];
		$file = new File();
		$file->load("*", array("id = :id", ":id" => $id));
		?>
			<div class="padx2 rf_media_edit">
				<div class="image text-center">
					<img src="<?= $file->original; ?>" alt="<?= $file->name; ?>">
					<br class="clear">
					<strong class="name pad1"><?= $file->name.".".$file->extension; ?></strong>
				</div>

				<form class="details" method="POST" action="/admin/rf_media/save/<?= $id; ?>">
					<input type="text" disabled value="<?= $file->original; ?>">
					<div class="formsec">
						<label for="">Title</label>
						<input type="text" name="name" value="<?= $file->name; ?>">
					</div>

					<br>
					<input type="submit" value="Save">

					<br><br>

					<a href="/admin/rf_media/regenerate/<?= $id; ?>" class="regenerate btn display-block pull-left">Regenerate</a>
					<a href="/admin/media/delete/<?= $id; ?>" class="delete btn-error display-block pull-right">Delete</a>
					<div class="clear"></div>

				</form>
			</div>
		<?
	}

	function save() {

	}

	function select_button($key) {
		?>
		<a href="/admin/rf_media/select" class="btn rf_media_browse" data-modal data-key="<?= $key; ?>">Select Image</a>
		<?
	}

	// resize file code
	// $img = new Image($raw, false, $root);
	// 		$img_name = "{$name}.{$size['name']}.{$ext}";
	// 		$img->resize($size['width'], $size['height'], $size['crop'], $size['enlarge']);

	// 		$path = $entry->upload($img, $img_name, $ext);
	// 		$images[$size['name']] = $path;

	function get_uploads() {
		global $db;
		$file = new File();
		$media = $file->find("*", null, array("ORDER BY created DESC, modified DESC"));

		return $media;
	}

	/**
	 * Display gallery widget
	 */
	function select() {
		$this->display("select");
	}
	function display($mode = "browse", $size = "2") {
		?>
		<div class="row g1">
			<div class="rf_media os mode_<?= $mode; ?>">
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
							$f = new File();
							$f->factory($file);

							$bg = $f->get_size(400); //$file['original'];
							if ($file['type'] == "file") {
								$bg = "/core/img/".$file['extension']."_default.png";
							}
							?>
							<div class="os-lg-<?= $size; ?> os-md-3 os-sm-4">
								<div class="file_card square">
									<img src="/core/assets/img/image_placeholder.jpg" data-original="<?= $f->original; ?>" data-src="<?= $bg; ?>" alt="<?= $file['name']; ?>" class="bg lazy">
									<a href="/admin/rf_media/edit/<?= $file['id']; ?>" data-id="<?= $file['id']; ?>" class="overlay"></a>
								</div>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
			<div class="os-3 hidden rf_media_editor">
				<div class="section autosticky inner_content"></div>
			</div>
		</div>
		<?
	}
}

Media::instance();
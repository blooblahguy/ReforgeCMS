<?

class File extends RF_Model {
	private $files = array("pdf");
	private $images = array("png", "jpg", "jpeg", "svg");

	// Register with Model/Schema
	function __construct($file = false) {
		$this->model_table = "rf_media";
		$this->model_schema = array(
			"uid" => array(
				"type" => "INT(32)"
			),
			"original" => array(
				"type" => "VARCHAR(256)"
			),
			"images" => array(
				"type" => "LONGTEXT"
			)
		);

		parent::__construct();
	}

	function get_uid($string) {
		return crc32($string);
	}

	/**
	 * Upload File
	 */
	function upload($object, $name, $ext) {
		if (in_array($ext, $this->images)) {
			$file = $object;
			$path = "images/".Date("Y")."/{$name}";
		} elseif (in_array($ext, $this->files)) {

		} else {
			Alerts::instance()->error("File ".$name." is invalid type");
			return;
		}

		// Save in Media
		$url = Media::instance()->save($path, $file);

		return $url;
	}


	/**
	 * Create ID Image
	 */
	function create_id_img($string) {
		// Create image from string
		$img = new Image();
		$img = $img->identicon($string, 100, 3);

		// Now set file upload path
		$path = "avatars/{$string}_avatar_auto.png";

		// Save in Media
		$url = Media::instance()->save($path, $img);

		return $url;
	}
}

class Media extends Prefab {
	const MODE = 0755;
	public $sizes = array();
	public $compression = 9;
	function __construct() {}

	/**
	 * Get path from absolute file
	 */
	private function get_path($absolute) {
		$base = pathinfo($absolute, PATHINFO_DIRNAME );

		return substr($absolute, 0, -strlen($base));
	}

	/**
	 * Get file extension from path
	 */
	function get_ext($path) {
		return strtolower(pathinfo($path, PATHINFO_EXTENSION));
	}

	/**
	 * Save data to path
	 */
	function save($path, $data) {
		global $core;

		if (is_object($data)) {
			// collect extension
			$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
			// create data from image object
			$data = $data->dump($ext, $this->compression);
		}

		// create path directory
		$dir_path = pathinfo($path, PATHINFO_DIRNAME);
		$this->ensure_directory($dir_path);

		// save data to path
		$core->write(uploads_dir().$path, $data);

		return uploads_url().$path;
	}



	/**
	 * Create Directories that don't exist
	 */
	function ensure_directory($path) {
		if (! is_dir($path)) {
			mkdir($path, $this::MODE, true);
		}
	}

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

	function upload_image($file) {
		global $root;

		// seperate out these values to build a name
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$name = substr($file['name'], 0, -strlen($ext)-1);
		$raw_name = "{$name}.raw.{$ext}";

		// load database object
		$entry = new File();
		$uid = $entry->get_uid($raw_name);
		$entry->load("`uid` = $uid");
		$entry->uid = $uid;

		// Raw Image Storage
		$raw = new Image($file['tmp_name'], false, '');
		$raw = $entry->upload($raw, $raw_name, $ext);
		$entry->original = $raw;

		// Loop through sizes
		$images = array();
		foreach ($this->sizes as $size) {
			$img = new Image($raw, false, $root);
			$img_name = "{$name}.{$size['name']}.{$ext}";
			$img->resize($size['width'], $size['height'], $size['crop'], $size['enlarge']);

			$path = $entry->upload($img, $img_name, $ext);
			$images[$size['name']] = $path;
		}

		$entry->images = serialize($images);
		$entry->save();



		// $name = PATHINFO_BASENAME()
		

		// debug($name);
		// debug($file);

		// $name = PATHINFO_BASENAME

		// $raw = $file->upload($name);

		// $name = basename($file['name']);
		// $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		// $name = substr($name, 0, -strlen($ext)-1);

		// $upload_dir = uploads_dir().Date("Y")."/";
		// $upload_url = uploads_url().Date("Y")."/";


		// // not allowed type
		// $allowed = array("png", "pdf", "jpg", "gif", "jpeg");
		// // var_dump($file_type);
		// if (! in_array($ext, $allowed)) {
		// 	Alerts::instance()->error("File type not allowed");
		// 	return false;
		// }

		// // not technically an image
		// $is_image = getimagesize($file["tmp_name"]);
		// if ($is_image === false) {
		// 	Alerts::instance()->error("File type not allowed. Not an image");
		// 	return false;
		// }

		// // clear to create/upload types
		// $raw = $upload_dir.$name."_raw.$ext";
		// move_uploaded_file($file["tmp_name"], $raw);
		// // $core->write($upload_dir.$name."_raw.$ext", $raw->dump($ext, 9));
		// // debug($raw);
		// foreach ($this->sizes as $size) {
		// 	$img = new Image($raw, false, "");
		// 	$img->resize($size['width'], $size['height'], $size['crop'], $size['enlarge']);
		// 	$path = $upload_dir.$name."_{$size['name']}.$ext";
		// 	// debug($path);

		// 	// $core->write($path, $img->dump($ext, 7));
		// 	// $img->render();
		// 	// debug($size);
		// 	// $img = new Image()
		// 	// debug($size);
		// }
	}


}





?>
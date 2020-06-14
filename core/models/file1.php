<?php

class File extends \RF\Mapper {
	const MODE = 0755;

	private $noresize = array("svg");
	private $images = array("png", "jpg", "svg");
	private $files = array("pdf", "zip", "rar");

	private $original_path = "/content/uploads/";
	private $size_path = "/content/uploads/sizes/";

	function __construct() {
		// set basic paths
		$this->original_path = $this->original_path;
		$this->size_path = $this->size_path;

		// model schema
		$schema = array(
			"hash" => array(
				"type" => "VARCHAR(32)",
				"unique" => true
			),
			"type" => array(
				"type" => "VARCHAR(100)"
			),
			"folder" => array(
				"type" => "INT(7)"
			),
			"extension" => array(
				"type" => "VARCHAR(100)"
			),
			"name" => array(
				"type" => "VARCHAR(256)"
			),
			"original" => array(
				"type" => "VARCHAR(256)"
			),
			"dimensions" => array(
				"type" => "VARCHAR(256)"
			),
			"sizes" => array(
				"type" => "LONGTEXT"
			)
		);

		parent::__construct("rf_media", $schema);
	}

	/**
	 * ensureDirectory
	 */
	function ensureDirectory($path) {
		if (! is_dir($path)) {
			mkdir($path, $this::MODE, true);
		}
	}

	/**
	 * canUpload
	 * ensure user has capabilities
	 */
	function canUpload() {
		$user = current_user();
		return $user->can("upload_files");
	}

	/**
	 * afterdelete
	 * Ensures files are deleted after the file is deleted in the database
	 */
	function afterdelete() {
		global $root;
		$sizes = unserialize($this->sizes);

		// delete original
		@unlink($root.$this->original);
		
		// delete custom sizes
		foreach ($sizes as $size) {
			@unlink($root.$this->size_path.$this->size['file']);
		}
	}

	/**
	 * Hash image contents for unique id
	 */
	function fileHash($path) {
		return hash_file("md5", $path);
	}

	/**
	 * createSize
	 * creates a new size based on original
	 */
	function createSize($width, $height) {
		global $root, $core;

		// load original image to base it on
		$img = new Image($this->original, false, $root);
		$img->resize($width, $height, true, false);

		// variables
		$name = basename($this->original);
		$name = str_replace(".raw.", ".", $name);
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$dims = array($height, $width);
		$dims = implode("x", $dims);
		$name = "{$name}.{$dims}.{$ext}";

		// build a size entry
		$size = array(
			"width" => $width,
			"height" => $height,
			"file" => $this->size_path.$name,
		);

		// write the file
		$compression = $this->compression;
		if ($ext == "jpg") { 
			$ext = "jpeg";
			$compression = ($compression + 1) * 10;
		}
		$core->write($this->size_path.$name, $img->dump($ext, $compression));

		// save the size into database
		$this->sizes[] = $size;
		$this->update();

		return $this->size_path.$name;
	}

	/**
	 * getSize
	 * Retreives a size automatically and stores it
	 */
	function getSize(int $width = null, int $height = null) {
		if (in_array($this->extension, $this->noresize)) {
			return $this->original;
		}

		if (in_array($this->extension, $this->files)) {
			return $this->original;
		}

		// if the dimensions are greater than the original, return the original
		list($dim_x, $dim_y) = unserialize($this->dimensions);
		if ($width && ! $height && $width >= $dim_x) {
			return $this->original;
		} elseif ($height && ! $width && $height >= $dim_y) {
			return $this->original;
		}

		// sizes
		$sizes = unserialize($this->sizes);
		$target = false;
		foreach ($sizes as $size) {
			if ($width && $width > 0 && $height && $height > 0) {
				if ($size['width'] == intval($width) && $size['height'] == intval($height)) {
					$target = $size;
					break;
				}
			} elseif ($width && $width > 0) {
				if ($size['width'] == intval($width)) {
					$target = $size;
					break;
				}
			} elseif ($height && $height > 0) {
				if ($size['height'] == intval($height)) {
					$target = $size;
					break;
				}
			}
		}

		// return if we found it
		if ($target) {
			return $target['path'];
		}

		// if not, let's create a new size
		return $this->createSize($width, $height);

		// check upload dirs
		$this->ensureDirectory($this->size_path);
	}

	/**
	 * getFile
	 * returns original file
	 */
	function getFile($file) {
		return $this->original;
	}

	/**
	 * setFile
	 * Takes a $_FILE and uploads it + sets it
	 */
	function setFile($file) {
		global $root; 

		$tmp = $file['tmp_name'];
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$hash = $this->fileHash($tmp);
		$name = str_replace('.raw.', ".", $file['name']);
		$name = str_replace(".$ext", "", $name);
		
		// try and load existing file from database
		$file = new File();
		$file->load("*", "hash = '{$hash}'");
		$file->name = $name;
		$file->extension = $ext;
		$file->hash = $hash;

		// Image
		if (in_array($ext, $this->images)) {
			$original = "{$name}.raw.{$ext}";
			$file->type = "image";
		} elseif (in_array($ext, $this->files)) {
			$original = "{$name}.{$ext}";
			$file->type = "file";
		} else {
			echo "File ".$name." is invalid type";
			return;
		}

		// check upload dirs
		$this->ensureDirectory($root.$this->original_path);

		// move the uploaded file
		move_uploaded_file($tmp, uploads_dir().$original);
		
		// set the original path of file
		$file->original = $root.$this->original_path.$original;

		// set its dimensions
		$img = new Image($original, false, $root);
		$file->dimensions = serialize(array($img->width, $img->height));

		// save the file
		$file->save();
	}

}
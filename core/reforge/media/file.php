<?php

class RF_File extends RF_Model {
	private $noresize = array("svg");

	function __construct() {
		$this->model_table = "rf_media";
		$this->model_schema = array(
			"hash" => array(
				"type" => "VARCHAR(32)",
				"unique" => true
			),
			"type" => array(
				"type" => "VARCHAR(100)"
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
			"sizes" => array(
				"type" => "LONGTEXT"
			)
		);

		parent::__construct();
	}

	function erase($filter = NULL, $quick = true) {
		global $root;
		$sizes = unserialize($this->sizes);
		@unlink("{$root}{$this->original}");
		foreach ($sizes as $size) {
			@unlink("{$root}{$size['path']}");
		}

		return parent::erase($filter);
	}

	function sort_sizes($sizes, $height, $width) {

		if ($width) {
			usort($sizes, function($a, $b) {
				return $a['width'] > $b['width'];
			});
		}
		if ($height) {
			usort($sizes, function($a, $b) {
				return $a['height'] > $b['height'];
			});
		}

		return $sizes;
	}

	function create_size($width, $height) {
		// debug("create size");
		$media = RF_Media::instance();
		$regs = $media->sizes;
		$regs = $this->sort_sizes($regs, $width, $height);

		$target = false;
		foreach ($regs as $dimension) {
			if ($width) {
				if ($dimension['width'] >= $width) {
					$target = $dimension;
					break;
				}

			} elseif ($height) {
				if ($dimension['height'] >= $height) {
					$target = $dimension;
					break;
				}
			}
		}

		if ($target) {
			// debug($target);

			$path = $media->save_size($target['name'], $this->original);

			$sizes = unserialize($this->sizes);
			$sizes[$target['name']] = $target;
			$sizes[$target['name']]['path'] = $path;

			$this->sizes = serialize($sizes);
			$this->query("UPDATE {$this->model_table} SET sizes = '{$this->sizes}' WHERE id = {$this->id}");
		}
	}

	function get_size($width = null, $height = null) {
		if (in_array($this->extension, $this->noresize)) {
			return $this->original;
		}
		$media = RF_Media::instance();
		$sizes = unserialize($this->sizes);
		if (! $sizes) {
			$sizes = array();
		}

		if (! $sizes || count($sizes) == 0) {
			$this->create_size($width, $height);
		}

		$sizes = $this->sort_sizes($sizes, $width, $height);

		$target = false;
		foreach ($media->sizes as $dimension) {
			if ($width) {
				if ($dimension['width'] >= $width) {
					$target = $dimension;
					break;
				}

			} elseif ($height) {
				if ($dimension['height'] >= $height) {
					$target = $dimension;
					break;
				}
			}
		}

		$found = false;
		foreach ($sizes as $dim) {
			if ($dim['name'] = $target['name']) {
				$found = $dim;
				break;
			}
		}

		if (! $found) {
			$this->create_size($width, $height);
		}

		return $dim['path'];
	}

	/**
	 * Create ID Image
	 */
	function create_id_img($string) {
		global $core;
		// Create image from string
		$img = new Image();
		$img = $img->identicon($string, 100, 3);
		$name = "{$string}_avatar_auto.png";

		// Now set file upload path
		$path = uploads_dir().$name;
		$url = uploads_url().$name;

		// Save in Media
		$core->write($path, $img->dump("png", 9));

		return $url;
	}
}

function get_file_size($id, $width = null, $height = null) {
	$file = new RF_File();
	$file->load("id = $id");

	return $file->get_size($width, $height);
}

function get_file($id) {
	if (! $id) { return false; }
	$arr = array();

	$file = new RF_File();
	$file->load("id = $id");

	$arr['id'] = $file->id;
	$arr['name'] = $file->name;
	$arr['original'] = $file->original;
	$arr['sizes'] = unserialize($file->sizes);

	return $arr;
}

?>
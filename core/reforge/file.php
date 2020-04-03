<?php

class File extends \RF\Mapper {
	private $noresize = array("svg");

	function __construct() {
		$schema = array(
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

		parent::__construct("rf_media", $schema);
	}

	function erase($filter = null, $quick = true) {
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
		$media = Media::instance();
		$regs = $media->sizes;
		$regs = $this->sort_sizes($regs, $width, $height);

		$target = false;
		foreach ($regs as $dimension) {
			if ($width && $width > 0) {
				if ($dimension['width'] >= (int) $width) {
					$target = $dimension;
					break;
				}

			} elseif ($height && $height > 0) {
				if ($dimension['height'] >= (int) $height) {
					$target = $dimension;
					break;
				}
			}
		}

		if ($target) {
			$path = $media->save_size($target['name'], $this->original);

			$sizes = unserialize($this->sizes);
			$sizes[$target['name']] = $target;
			$sizes[$target['name']]['path'] = $path;

			$this->sizes = serialize($sizes);
			$this->update();
			// $this->query("UPDATE {$this->table} SET sizes = '{$this->sizes}' WHERE id = {$this->id}");
		}
	}

	function get_size(int $width = null, int $height = null) {
		if (in_array($this->extension, $this->noresize)) {
			return $this->original;
		}
		$media = Media::instance();
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
			if ($dim['name'] == $target['name']) {
				$found = $dim;
				break;
			}
		}

		if (! $found) {
			$this->create_size($width, $height);
		}

		return $found['path'];
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

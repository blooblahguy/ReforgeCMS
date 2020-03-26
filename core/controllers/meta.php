<?
	class Meta extends \RF\Mapper {
		function __construct() {
			$schema = array(
				"meta_parent" => array(
					"type" => "INT(7)",
				),
				"meta_type" => array(
					"type" => "VARCHAR(255)",
				),
				"meta_key" => array(
					"type" => "VARCHAR(255)",
				),
				"meta_value" => array(
					"type" => "LONGTEXT",
				),
				"meta_info" => array(
					"type" => "VARCHAR(100)",
				),
				"created" => false,
				"modified" => false,
			);

			parent::__construct("post_meta", $schema);
		}
	}

	$meta_cache = array();

	function get_meta($uid, $key = false) {
		global $meta_cache;
		list($type, $post_id) = explode("_", $uid);
		if ($post_id === NULL) {
			$post_id = $type;
			$type = NULL;
			$uid = "post_{$post_id}";
		}

		if (! isset($meta_cache[$uid][$key])) {
			$meta = new Meta();
			$extra = "";
			$vars = array(
				":pid" => $post_id,
			);
			if ($type) {
				$extra .= "AND meta_type = :type ";
				$vars[":type"] = $type;
			}
			if ($key) {
				$extra .= "AND meta_key = :key ";
				$vars[":key"] = $key;
			}

			$meta = $meta->query("SELECT * FROM {$meta->model_table} WHERE meta_parent = :pid $extra", $vars);
			$meta = array_extract($meta, "meta_key", "meta_value");

			if ($key !== false) {
				$meta_cache[$uid][$key] = $meta[$key];
			} else {
				foreach ($meta as $k => $v) {
					$meta_cache[$uid][$k] = $v;
				}
			}
		}

		if ($key) {
			return $meta_cache[$uid][$key];		
		} else {
			return $meta_cache[$uid];		
		}

	}

	function set_meta($uid, $key, $value) {
		$meta = new Meta();
		list($type, $post_id) = explode("_", $uid);
		if ($post_id === NULL) {
			$post_id = $type;
			$type = NULL;
			$uid = "post_{$post_id}";
		}

		$extra = "";
		$vars = array(
			":pid" => $post_id,
		);
		if ($type) {
			$extra .= "AND meta_type = :type ";
			$vars[":type"] = $type;
		}
		if ($key) {
			$extra .= "AND meta_key = :key ";
			$vars[":key"] = $key;
		}

		$query = array("meta_parent = :pid $extra");
		$query = array_merge($query, $vars);
		$meta->load($query);

		$meta->meta_parent = $post_id;
		$meta->meta_key = $key;
		$meta->meta_type = $type;
		$meta->meta_value = $value;
		$meta->save();

		$meta_cache[$uid][$key] = $value;
	}
?>
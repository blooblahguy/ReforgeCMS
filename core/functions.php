<?
	function display_alerts($level = "all") {
		\Alerts::instance()->display($level);
	}

	function add_alert($type, $message) {
		\Alerts::instance()->$$type($message);
	}

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

			$meta = $meta->query("SELECT * FROM {$meta->table} WHERE meta_parent = :pid $extra", $vars);
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

	function plugins_dir() {
		global $root;
		return $root."/content/plugins";
	}
	function theme_dir() {
		global $root;
		return $root."/content/themes/".get_option("active_theme");
	}
	function theme_url() {
		return "/content/themes/".get_option("active_theme");
	}

	function theme_path() {
		return $_SERVER['DOCUMENT_ROOT']."/content/themes/".get_option("active_theme")."/";
	}
	
	function register_post_type($options) {
		global $rf_custom_posts;

		// debug($options);
		$slug = $options['slug'];
		// echo $slug;

		$info = array(
			"order" => 5 + $options["order"],
			"statuses" => serialize($options["statuses"]),
			"icon" => $options["icon"],
			"slug" => $options["slug"],
			"label" => $options["label"],
			"label_plural" => $options["label_plural"],
			"base_permission" => array("update_any_{$options['slug']}", "update_own_{$options['slug']}"),
			"route" => "/admin/posts/@slug",
			"link" => "/admin/posts/{$slug}",
		);

		$rf_custom_posts[$slug] = $info;
	}

	$shortcodes = array();	
	function add_shortcode($tag, $callback) {
		global $shortcodes;
		if ( is_callable($callback) ) {
			$shortcodes[$tag] = $callback;
		}
	}
	
	function remove_shortcode($tag) {
		global $shortcodes;
		unset($shortcodes[$tag]);
	}
	
	function parse_shortcodes($fullcontent) {
		global $shortcodes;

		if (gettype($fullcontent) !== "string") { return $fullcontent; }
		
		$fullcontent = stripslashes($fullcontent);
		
		// if we don't have shortcodes or can't even find a bracket then we know there are none
		if (strpos( $fullcontent, '[' ) === false || empty($shortcodes)) {
			return $fullcontent;
		}
		
		$tagnames = array_keys($shortcodes);
		$tagregexp = join( '|', array_map('preg_quote', $tagnames) );

		foreach ($shortcodes as $tag => $callback) { 
			$regex = "/\[$tag(.*?)\]/";
			
			$fullcontent = preg_replace_callback($regex, function($matches) {
				global $shortcodes;
				
				// thank you wordpress
				$atts = array();
				$full = $matches[0];

				$tag = explode(" ", $full);
				$tag = $tag[0];
				$tag = str_replace("[","",$tag);
				$tag = str_replace("]","",$tag);
				
				$callback = $shortcodes[$tag];
				
				if (isset($matches[1])) {
					$attributes = trim($matches[1]);
					$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
					$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $attributes);
					
					if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
						foreach ($match as $m) {
							if (!empty($m[1]))
								$atts[strtolower($m[1])] = stripcslashes($m[2]);
							elseif (!empty($m[3]))
								$atts[strtolower($m[3])] = stripcslashes($m[4]);
							elseif (!empty($m[5]))
								$atts[strtolower($m[5])] = stripcslashes($m[6]);
							elseif (isset($m[7]) and strlen($m[7]))
								$atts[] = stripcslashes($m[7]);
							elseif (isset($m[8]))
								$atts[] = stripcslashes($m[8]);
						}
					} else {
						$atts = ltrim($text);
					}
				}

				ob_start();
				$callback($atts);
				$content = ob_get_contents();
				ob_get_clean();

				return $content;
			
			}, $fullcontent);
		}
		
		return $fullcontent;
	}


	function array_extract($array, $key, $value) {
		$new = array();
		foreach ($array as $v) {
			$new[$v[$key]] = $v[$value];
		}

		return $new;
	}
	function rekey_array($key, $array) {
		$new = array();
		foreach ($array as $v) {
			$new[$v[$key]] = $v;
		}

		return $new;
	}

	
	function dequeue_style($path) {
		global $rf_styles;
		foreach ($rf_styles as $priority => $styles) {
			foreach ($rf_styles[$priority] as $key => $style) {
				if ($style == $path) {
					unset($rf_styles[$priority][$key]);
				}
			}
		}
	}
	function dequeue_script($path) {
		global $rf_scripts;
		foreach ($rf_scripts as $priority => $scripts) {
			foreach ($rf_scripts[$priority] as $key => $script) {
				if ($script == $path) {
					unset($rf_scripts[$priority][$key]);
				}
			}
		}
	}

	function queue_scss($path, $priority = 10) {
		global $rf_scss;

		if (! isset($rf_scss[$priority])) {
			$rf_scss[$priority] = array();
		}

		$rf_scss[$priority][] = $path;
	}
	function queue_style($path, $priority = 10) {
		global $rf_styles;

		if (! isset($rf_styles[$priority])) {
			$rf_styles[$priority] = array();
		}

		$rf_styles[$priority][] = $path;
	}

	function queue_script($path, $priority = 10) {
		global $rf_scripts;

		// debug($path);

		if (! isset($rf_scripts[$priority])) {
			$rf_scripts[$priority] = array();
		}

		$rf_scripts[$priority][] = $path;
	}

	function rf_styles() {
		global $rf_styles;
		ksort($rf_styles);

		// print out queued styles
		if (isset($rf_styles)) {
			foreach ($rf_styles as $priority => $styles) {
				foreach ($styles as $k => $path) { ?>
					<link rel="stylesheet" href="<? echo $path; ?>">
				<? }
			}
		}
	}

	function rf_scripts() {
		global $rf_scripts;
		ksort($rf_scripts);

		foreach ($rf_scripts as $priority => $scripts) {
			foreach ($scripts as $k => $file) {
				echo '<script src="'.$file.'"></script>';
			}
		}
	}

	// function get_field()

	function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string), '_'));
    }

	function repeater_existing($key) {
		return isset($_POST[$key]) ? $_POST[$key] : array();
	}
	function repeater_new($key, ...$fields) {
		$new_entries = array();
		if ($_POST[$key]) {
			foreach ($_POST[$key] as $k => $v) {
				$row = array();
				foreach ($fields as $field) {
					$row[$field] = isset($_POST[$field][$k]) ? ($_POST[$field][$k] ? $_POST[$field][$k] : 1) : 0;
				}
				$new_entries[] = $row;
			}
		}

		return $new_entries;
	}

	function debug(...$params) {
		echo "<pre>";
		foreach ($params as $p) {
			print_r($p);
			echo "\n";
		}
		echo "</pre>";
	}

	function redirect($path) {
		header("Location: ".$path);
		exit();
	}

	// svg includes
	function get_file_contents_url($url) {
		if (strpos($url, $_SERVER['HTTP_HOST']) !== false) {
			echo "strip";
			$url = explode($_SERVER['HTTP_HOST'], $url);
			$url = $url[1];
			return file_get_contents($_SERVER['DOCUMENT_ROOT'].$url);
		} else {
			return file_get_contents($_SERVER['DOCUMENT_ROOT'].$url);
		}
	}
?>
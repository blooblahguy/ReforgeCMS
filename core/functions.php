<?
	function rekey_array($key, $array) {
		$new = array();
		foreach ($array as $v) {
			$new[$v[$key]] = $v;
		}

		return $new;
	}

	$rf_styles = array();
	$rf_scripts = array();
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
		global $core;
		global $rf_scripts;
		ksort($rf_scripts);


		$scripts = $core->get("scripts");
		if (! $scripts) {
			$files = array();
			$scripts_by_dirs = array();
			foreach ($rf_scripts as $priority => $scripts) {
				foreach ($scripts as $k => $file) {
					$files[] = $_SERVER["DOCUMENT_ROOT"].$file;
			// 		$path = $_SERVER['DOCUMENT_ROOT'].dirname($file)."/";
			// 		// debug($path, basename($file));
			// 		$scripts_by_dirs[$path][] = basename($file);
				}
			}
			// debug($files);

			// foreach ($scripts_by_dirs as $path => $files) {
				// debug($files);
				// $files = rtrim($files, ",");
				// $scripts = trim(Web::instance()->minify($files, null, false, ''));
			// }
			// $core->set("scripts", $scripts);
		}

		echo "<script>$scripts</script>";
	}

	function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string), '_'));
    }

	function display_results_table($rs, $fields) { ?>
		<table class="even">
			<thead>
				<tr>
					<? foreach ($fields as $f) { ?>
						<th class="<?= $f["class"]; ?>"><?= $f["label"]; ?></th>
					<? } ?>
				</tr>
			</thead>
			<tbody>
				<? foreach ($rs as $k => $r) { ?>
					<tr>
						<? 
						foreach ($fields as $i => $f) { ?>
							<td class="<?= $f["class"]; ?>">
								<? if ($f["calculate"]) {
									echo $f["calculate"]($r[$i], $r["id"]);
								} elseif ($f["html"]) {
									echo sprintf($f["html"], $r[$i], $r["id"]);
								} else {
									echo $r[$i]; 
								} ?>
							</td>
						<? } ?>
					</tr>
				<? } ?>
			</tbody>
		</table>
	<? }
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

	function the_field() {

	}

	function get_field() {
		
	}

	function debug(...$params) {
		foreach ($params as $p) {
			echo "<pre>";
			print_r($p);
			echo "</pre>";
		}
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
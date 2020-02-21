<?
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

	function the_header() {
		global $user;
		global $menu;
		global $core;
		global $ROOT;
		// $view = new View;
        // echo $view->render('template.htm');
		require_once($ROOT."/content/header.php");
	}

	function the_footer() {
		global $user;
		global $menu;
		global $core;
		global $ROOT;
		// $view = new View;
        // echo $view->render('template.htm');
		require_once($ROOT."/content/footer.php");
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
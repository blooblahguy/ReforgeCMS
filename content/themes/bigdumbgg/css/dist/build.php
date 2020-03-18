<?php
	

	// function debug($i) {
	// 	print_r($i);
	// 	echo "\n";
	// 	echo "\n";
	// }

	// cached updating
	$update = false;
	$cache_mod = filemtime($out_file);
	if (! $cache_mod) {
		$fh = fopen($out_file, 'w');
	}
	$this_mod = filemtime(__FILE__);
	// debug($cache_mod);
	foreach ($sheets as $sheet) {
		// debug($sheet);
		// if (filemtime($sheet) > $cache_mod) {
		// 	// debug("higher than out file");
		// }
		// if ($this_mod > $cache_mod) {
		// 	// debug("higher than current file");
		// }
		if (filemtime($sheet) > $cache_mod || $this_mod > $cache_mod) {
			$update = true;
			break;
		}
	}

	// debug($update);


	use Leafo\ScssPhp\Compiler;
	if ($update) {
		require $root.'/core/css/scssphp/scss.inc.php';

		$scss = new Compiler();
		$scss->setImportPaths('');

		ob_start();
		foreach($sheets as $s) {
			require_once($s);
		}
		$css_all = ob_get_contents();
		ob_end_clean();

		// 1 minified
		$scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
		$data = $scss->compile($css_all);
		file_put_contents($out_file, $data);
	}

	include($out_file);
?>
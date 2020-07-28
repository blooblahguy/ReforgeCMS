<?php
	header('Content-Type: text/css');

	// cached updating
	$update = false;
	$cache_mod = filemtime($out_file);
	$this_mod = filemtime(__FILE__);
	if (! $cache_mod) {
		$fh = fopen($out_file, 'w');
	}
	
	foreach ($sheets as $sheet) {
		if (filemtime($sheet) > $cache_mod || $this_mod > $cache_mod) {
			$update = true;
			break;
		}
	}
	
	if ($update) {
		require $root.'/core/assets/css/scssphp/scss.inc.php';

		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

		$scss = new Leafo\ScssPhp\Compiler();
		$scss->setImportPaths('');

		ob_start();
		foreach($sheets as $s) {
			require $s;
		}
		$css_all = ob_get_contents();
		ob_end_clean();

		// 1 minified
		$scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
		$data = $scss->compile($css_all);

		file_put_contents($out_file, $data);
	}

	require $out_file;

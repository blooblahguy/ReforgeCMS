<?php

function register_partial($slug, $title, $content) {

}

function get_partial($slug) {
	$partial = new Partial();
	$partial->load(array("post_type = :pt AND slug = :slug", ":slug" => $slug, ":pt" => "partial"));
	$uid = $partial->id;
	$cache = (int) get_meta($uid, 'cache');
	$html = $partial->cache['queries']->get($slug . "_html");

	// debug($slug, $cache);

	if ($cache != 0 && $html) {
		echo $html;
	} else {
	
		$template = get_template_part("partials", $slug, false);
		if ($template) {
			rf_require($template);
		} else {
			echo $partial->content;
		}
		
		// now ob store into cache
		if ($cache != 0) {
			ob_start();
			if ($template) {
				rf_require($template);
			} else {
				echo $partial->content;
			}
			$html = ob_get_contents();
			ob_end_clean();

			$partial->cache['queries']->set($slug . "_html", $html, $cache);
		}
	}


	
}
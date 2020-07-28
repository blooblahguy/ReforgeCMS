<?php

$data = get_data("https://www.youtube.com/feeds/videos.xml?channel_id=UCd8SMBW3G348O5jmMgmRRfA");
$xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$videos = json_decode($json,TRUE);

// debug($videos);
// 
$latest = reset($videos['entry']);

// debug($latest);
list($yt, $type, $id) = explode(":", $latest['id']);

?>

<div class="latest_yt">
	<iframe src="https://www.youtube.com/embed/<?= $id; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
<?php

// declare(strict_types=1);
require_once(theme_dir()."/lib/twitter-php/OAuth.php");
require_once(theme_dir()."/lib/twitter-php/twitter.php");

use DG\Twitter\Twitter;
$twitter = new Twitter(
	"iBYFUvcap4mwCscLSwD2uKMcQ", 
	"FYKI1i2LaR9cnrbROd5XT9YYeBteeubchkVB0wyqgRtAV8PRdW", 
	"829782641067032576-moAwrbTvBO0bgNS8GCj4HVearawhmNM", 
	"YhsCuheQL21Fu9b6lmhWZtJLARJDzkmqgIrPpUJafvcdK"
);

$statuses = $twitter->load(Twitter::ME, 4);

$user = array(
	"id" => $statuses[0]->user->id,
	"screen_name" => $statuses[0]->user->screen_name,
	"name" => $statuses[0]->user->name,
	"url" => $statuses[0]->user->url,
	"icon" => $statuses[0]->user->profile_image_url_https,
	"followers" => $statuses[0]->user->followers_count,
);

$tweets = array();
foreach ($statuses as $status) {
	$tweet = array(
		"message" => Twitter::clickable($status),
		"posted_at" => $status->created_at,
		"posted_by" => $status->user->name,
		"link" => "https://twitter.com/bigdumbgaming/status/".$status->id,
	);

	$tweets[] = $tweet;
}

$result = array(
	"tweets" => $tweets,
	"user" => $user,
);

set_option("bdg_tweets", serialize($result));
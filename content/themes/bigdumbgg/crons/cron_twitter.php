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

$statuses = $twitter->load($twitter::ME, 4);

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
	// debug($status);
	$tweet = array(
		"user_nickname" => $status->user->name,
		"user_name" => $status->user->screen_name,
		"user_url" => $status->user->url,
		"url" => "https://twitter.com/i/web/status/".$status->id,
		"profile_image_url" => $status->user->profile_image_url,
		"text" => Twitter::clickable($status),
		"created_at" => $status->created_at,
		"link" => "https://twitter.com/bigdumbgaming/status/".$status->id,
	);

	if (isset($status->quoted_status)) {
		$tweet["quoted"] = array(
			"user_nickname" => $status->quoted_status->user->name,
			"user_name" => $status->quoted_status->user->screen_name,
			"user_url" => $status->quoted_status->user->url,
			"url" => "https://twitter.com/i/web/status/".$status->quoted_status->id,
			"profile_image_url" => $status->quoted_status->user->profile_image_url,
			"text" => Twitter::clickable($status->quoted_status),
			"created_at" => $status->quoted_status->created_at,
			"link" => "https://twitter.com/bigdumbgaming/status/".$status->quoted_status->id,
		);
	}

	$tweets[] = $tweet;
}

array_splice($tweets, 2, (count($tweets) - 2));

$result = array(
	"tweets" => $tweets,
	"user" => $user,
);

set_option("bdg_tweets", serialize($result));
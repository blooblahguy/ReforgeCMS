<?php

// declare(strict_types=1);
require $root."/content/themes/bigdumbgg/lib/twitter-php/OAuth.php";
require $root."/content/themes/bigdumbgg/lib/twitter-php/twitter.php";

use DG\Twitter\Twitter;
$twitter = new Twitter(
	"iBYFUvcap4mwCscLSwD2uKMcQ", 
	"FYKI1i2LaR9cnrbROd5XT9YYeBteeubchkVB0wyqgRtAV8PRdW", 
	"829782641067032576-moAwrbTvBO0bgNS8GCj4HVearawhmNM", 
	"YhsCuheQL21Fu9b6lmhWZtJLARJDzkmqgIrPpUJafvcdK"
);

$statuses = $twitter->load($twitter::ME, 20);

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
	// if (strlen($status->in_reply_to_status_id) > 1) {
	// 	continue;
	// }

	// create clickables
	$status->text = Twitter::clickable($status);
	$status->quoted_status = Twitter::clickable($status);

	$tweet = array(
		"user_nickname" => $status->user->name,
		"user_name" => $status->user->screen_name,
		"user_url" => $status->user->url,
		"url" => "https://twitter.com/i/web/status/".$status->id,
		"profile_image_url" => $status->user->profile_image_url,
		"text" => json_encode($status->text),
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
			"text" => json_encode($status->quoted_status),
			"created_at" => $status->quoted_status->created_at,
			"link" => "https://twitter.com/bigdumbgaming/status/".$status->quoted_status->id,
		);
	}

	$tweets[] = $tweet;
}

// debug($tweets);
$tweets = array_splice($tweets, 0, 3);

$result = array(
	"tweets" => $tweets,
	"user" => $user,
);
// debug($result);

set_option("bdg_tweets", $result);
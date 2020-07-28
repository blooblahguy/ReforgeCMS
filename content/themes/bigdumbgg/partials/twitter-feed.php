<?php

//==================================================================
// DISPLAY
//==================================================================
$tweets = get_option('bdg_tweets');
$user = $tweets['user'];
$tweets = $tweets['tweets'];

foreach ($tweets as $t) { 
	tweet_template($t);
}
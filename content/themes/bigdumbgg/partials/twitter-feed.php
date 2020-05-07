<?php

require_once(theme_dir()."/lib/tweet-php/TweetPHP.php");

$TweetPHP = new TweetPHP(array(
	'consumer_key' => 'iBYFUvcap4mwCscLSwD2uKMcQ',
	'consumer_secret' => 'FYKI1i2LaR9cnrbROd5XT9YYeBteeubchkVB0wyqgRtAV8PRdW',
	'access_token' => '829782641067032576-moAwrbTvBO0bgNS8GCj4HVearawhmNM',
	'access_token_secret' => 'YhsCuheQL21Fu9b6lmhWZtJLARJDzkmqgIrPpUJafvcdK',
	'tweets_to_display' => '3',
	'twitter_template' => '{tweets}',
	'tweet_template' => '<div class="tweet"><span class="status">{tweet}</span><span class="meta"><a href="{link}">{date}</a></span></div>',
	'api_params' => array('screen_name' => 'bigdumbgaming')
));

$recent_tweets = array();
$recent_tweets['tweets'] = $TweetPHP->get_tweet_list();
$recent_tweets['array'] = $TweetPHP->get_tweet_array();
$recent_tweets['array'] = array_slice($recent_tweets['array'], 0, 1);
set_option("bdg_tweets", $recent_tweets);

//==================================================================
// DISPLAY
//==================================================================
$tweets = get_option('bdg_tweets');
$tuser = $tweets['array'][0]['user'];

// debug($tuser);
?>
<div class="widget tweets">
	<div class="title">
		Twitter
		<a href="https://twitter.com/bigdumbgaming" target="_blank" class="pull-right trans-opacity">@<? echo $tuser['screen_name']; ?><img src="<? echo $tuser['profile_image_url_https']; ?>" alt="<? echo $tuser['name']; ?>"></a>
	</div>
	<div class="content">
		<? echo $tweets['tweets']; ?>
		<? /*foreach ($tweets as $tweet) {
			<div class="user">
				<img src="<? echo $tweet['user']['profile_image_url_https']; ?>" alt=""> <strong><a href="<? echo $tweet['user']['url']; ?>">@<? echo $tweet['user']['screen_name']; ?></a></strong>
			</div><?

			// debug($tweet['user']);
			echo $tweet['parsed'];
			// $created = $tweet['created'];
			
			// // replace links with anchors
			// $content = $tweet['text'];
			// foreach ($tweet['entities']['urls'] as $link) {
			// 	$content = substr_replace($content, '<a href="'.$link['expanded_url'].'" target="_blank">'.$link['display_url'].'</a>', $link['indices'][0], ($link['indices'][1] - $link['indices'][0]));
			// }
			// if ($tweet['entities']['media']) {
			// 	foreach ($tweet['entities']['media'] as $media) {
			// 		$content = substr_replace($content, '<img src="'.$media['media_url_https'].'" target="_blank" />', $media['indices'][0], ($media['indices'][1] - $media['indices'][0]));
			// 	}
			// } 

			// replace media with images
			
			
			<div class="tweet">
				<? //echo nl2br($content); ?>
				<div class="media">
					
				</div>
			</div>
			} */ ?>
	</div>
</div>
<? 
// debug($tweets);
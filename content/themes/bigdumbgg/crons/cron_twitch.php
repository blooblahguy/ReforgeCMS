<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$root = "/home/runcloud/webapps/bigdumb";


require $root."/vendor/autoload.php";




$twitch_client_id = 'so3d8hae0pmk8nz6kuwel6fhdbpofw3';
$twitch_client_secret = 'al1qneapful9ivkv6aiytx8ucq4y3d';
$twitch_scopes = '';

$helixGuzzleClient = new \NewTwitchApi\HelixGuzzleClient($twitch_client_id);
$newTwitchApi = new \NewTwitchApi\NewTwitchApi($helixGuzzleClient, $twitch_client_id, $twitch_client_secret);
$oauth = $newTwitchApi->getOauthApi();

try {
    $token = $oauth->getAppAccessToken($twitch_scopes ?? '');
    $data = json_decode($token->getBody()->getContents());

    // Your bearer token
    $twitch_access_token = $data->access_token ?? null;

    // The scopes from the API
	// print_r($data);
    // $twitch_scopes = $data->scope;
} catch (Exception $e) {
	//TODO: Handle Error
}

// get team streamers
$streamers = array();
$stream_ids = array();
try {
    // Make the API call. A ResponseInterface object is returned.
    $response = $newTwitchApi->getTeamsApi()->getTeams($twitch_access_token, "bdg");

    // Get and decode the actual content sent by Twitch.
    $responseContent = json_decode($response->getBody()->getContents());

	$data = $responseContent->data[0];

	foreach ($data->users as $user) {
		$user = json_decode(json_encode($user), true);
		$streamers[$user['user_id']] = $user;
		array_push($stream_ids, $user['user_id']);
	}

} catch (GuzzleException $e) {
	debug($e);
}

// now check who's online
try {
	// Make the API call. A ResponseInterface object is returned.
	$response = $newTwitchApi->getStreamsApi()->getStreams($twitch_access_token, $stream_ids, [], [], [], null, null, null, true);

	// Get and decode the actual content sent by Twitch.
	$responseContent = json_decode($response->getBody()->getContents());

	$data = $responseContent->data;
	
	$data = json_decode(json_encode($data), true);
	
	$live_streams = $data;
	if (count($live_streams) > 0) {
		$rand = array_rand($live_streams);
		$feature = $live_streams[$rand];
		unset($live_streams[$rand]);
		array_unshift($live_streams, $feature);
	}

} catch (GuzzleException $e) {
	print_r($e);
}

print_r($live_streams);

set_option("live_streams", $live_streams);
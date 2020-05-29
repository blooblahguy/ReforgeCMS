<?php

$discord_webhook = 'https://discordapp.com/api/webhooks/715993969645060209/vwhnDacM_Vlx7V92u8qa3nA19S-pbW1LQDPDgsn9eAAJ_6JawVPooAik9DjcxWF_UwlW';

function send_discord_app($app) {
	global $discord_webhook;
	$timestamp = date("c", strtotime("now"));
	$author = new User();
	$author->get_user($app->author);
	$meta = RCF()->get_fields("application", $app->id);//get_meta("application_".$app->id);

	// debug($meta);

	$json_data = json_encode([		
		"username" => "Application Bot",
		"tts" => false,
		"embeds" => [
			[
				"title" => $app->title,
				"type" => "rich",
				"url" => "https://bigdumb.gg/recruitment/applications/".$app->id,
				"timestamp" => $timestamp,
				"color" => hexdec( "e0b15a" ),
				"fields" => [
					[
						"name" => "Character",
						"value" => $meta['character_info']['character_name'],
						"inline" => false
					],
					[
						"name" => "Sever",
						"value" => $meta['character_info']['character_server'],
						"inline" => true
					]
				]
			]
		]
	
	], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	
	$ch = curl_init( $discord_webhook );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );

	curl_close( $ch );
}

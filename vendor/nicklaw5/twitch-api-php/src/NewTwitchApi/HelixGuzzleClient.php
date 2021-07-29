<?php

declare(strict_types=1);

namespace NewTwitchApi;

use GuzzleHttp\Client;

class HelixGuzzleClient
{
    private $client;
    private const BASE_URI = 'https://api.twitch.tv/helix/';

    public function __construct(string $clientId, array $config = [], string $baseUri = null)
    {
        if ($baseUri == null) {
            $baseUri = self::BASE_URI;
        }

        $headers = [
          'Client-ID' => $clientId,
          'Content-Type' => 'application/json',
        ];

        $client_config = [
            'base_uri' => $baseUri,
            'headers' => $headers,
        ];

        if (isset($config['handler'])) {
            $client_config = [];
        }

        $client_config = array_merge($client_config, $config);

        $this->client = new Client($client_config);
    }

    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }

    public function send($request)
    {
        return $this->client->send($request);
    }
}

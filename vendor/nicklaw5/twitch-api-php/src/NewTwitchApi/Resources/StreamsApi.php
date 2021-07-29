<?php

declare(strict_types=1);

namespace NewTwitchApi\Resources;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class StreamsApi extends AbstractResource
{
    /**
     * @throws GuzzleException
     */
    public function getStreamForUserId(string $bearer, string $userId): ResponseInterface
    {
        return $this->getStreams($bearer, [$userId]);
    }

    /**
     * @throws GuzzleException
     */
    public function getStreamForUsername(string $bearer, string $username): ResponseInterface
    {
        return $this->getStreams($bearer, [], [$username]);
    }

    /**
     * @throws GuzzleException
     */
    public function getStreamsByGameId(string $bearer, string $gameId, int $first = null, string $before = null, string $after = null): ResponseInterface
    {
        return $this->getStreams($bearer, [], [], [$gameId]);
    }

    /**
     * @throws GuzzleException
     */
    public function getStreamsByLanguage(string $bearer, string $language, int $first = null, string $before = null, string $after = null): ResponseInterface
    {
        return $this->getStreams($bearer, [], [], [], [$language]);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#get-stream-key
     */
    public function getStreamKey(string $bearer, string $broadcasterId): ResponseInterface
    {
        $queryParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];

        return $this->getApi('streams/key', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference/#get-streams
     */
    public function getStreams(string $bearer, array $userIds = [], array $usernames = [], array $gameIds = [], array $languages = [], int $first = null, string $before = null, string $after = null, bool $liveOnly = false): ResponseInterface
    {
        $queryParamsMap = [];
        foreach ($userIds as $id) {
            $queryParamsMap[] = ['key' => 'user_id', 'value' => $id];
        }
        foreach ($usernames as $username) {
            $queryParamsMap[] = ['key' => 'user_login', 'value' => $username];
        }
        foreach ($gameIds as $gameId) {
            $queryParamsMap[] = ['key' => 'game_id', 'value' => $gameId];
        }
        foreach ($languages as $language) {
            $queryParamsMap[] = ['key' => 'language', 'value' => $language];
        }
        if ($first) {
            $queryParamsMap[] = ['key' => 'first', 'value' => $first];
        }
        if ($before) {
            $queryParamsMap[] = ['key' => 'before', 'value' => $before];
        }
        if ($after) {
            $queryParamsMap[] = ['key' => 'after', 'value' => $after];
        }
		if ($liveOnly) {
            $queryParamsMap[] = ['key' => 'live_only', 'value' => $liveOnly];
        }

        return $this->getApi('streams', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference/#get-stream-markers
     */
    public function getStreamMarkers(string $bearer, string $userId = null, string $videoId = null, string $first = null, string $before = null, string $after = null): ResponseInterface
    {
        $queryParamsMap = [];

        if ($userId) {
            $queryParamsMap[] = ['key' => 'user_id', 'value' => $userId];
        }

        if ($videoId) {
            $queryParamsMap[] = ['key' => 'video_id', 'value' => $videoId];
        }

        if ($first) {
            $queryParamsMap[] = ['key' => 'first', 'value' => $first];
        }

        if ($before) {
            $queryParamsMap[] = ['key' => 'before', 'value' => $before];
        }

        if ($after) {
            $queryParamsMap[] = ['key' => 'after', 'value' => $after];
        }

        return $this->getApi('streams/markers', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference/#get-followed-streams
     */
    public function getFollowedStreams(string $bearer, string $userId, int $first = null, string $after = null): ResponseInterface
    {
        $queryParamsMap = [];

        $queryParamsMap[] = ['key' => 'user_id', 'value' => $userId];

        if ($first) {
            $queryParamsMap[] = ['key' => 'first', 'value' => $first];
        }

        if ($after) {
            $queryParamsMap[] = ['key' => 'after', 'value' => $after];
        }

        return $this->getApi('streams/followed', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#create-stream-marker
     */
    public function createStreamMarker(string $bearer, string $userId, string $description = null): ResponseInterface
    {
        $bodyParamsMap = [];

        $bodyParamsMap[] = ['key' => 'user_id', 'value' => $userId];
        if ($description) {
            $bodyParamsMap[] = ['key' => 'description', 'value' => $description];
        }

        return $this->postApi('streams/markers', $bearer, [], $bodyParamsMap);
    }
}

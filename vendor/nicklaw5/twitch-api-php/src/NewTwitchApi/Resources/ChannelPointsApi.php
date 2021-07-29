<?php

declare(strict_types=1);

namespace NewTwitchApi\Resources;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ChannelPointsApi extends AbstractResource
{
    /**
     * @throws GuzzleException
     */
    public function getCustomRewardById(string $bearer, string $broadcasterId, string $id, bool $onlyManageableRewards = null): ResponseInterface
    {
        return $this->getCustomReward($bearer, $broadcasterId, [$id], $onlyManageableRewards);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#get-custom-reward
     */
    public function getCustomReward(string $bearer, string $broadcasterId, array $ids = [], bool $onlyManageableRewards = null): ResponseInterface
    {
        $queryParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];

        foreach ($ids as $id) {
            $queryParamsMap[] = ['key' => 'id', 'value' => $id];
        }

        if ($onlyManageableRewards) {
            $queryParamsMap[] = ['key' => 'only_manageable_rewards', 'value' => $onlyManageableRewards];
        }

        return $this->getApi('channel_points/custom_rewards', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#get-custom-reward-redemption
     */
    public function getCustomRewardRedemption(string $bearer, string $broadcasterId, string $rewardId = null, array $ids = [], string $status = null, string $sort = null, string $after = null, string $first = null): ResponseInterface
    {
        $queryParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];

        if ($rewardId) {
            $queryParamsMap[] = ['key' => 'reward_id', 'value' => $rewardId];
        }

        foreach ($ids as $id) {
            $queryParamsMap[] = ['key' => 'id', 'value' => $id];
        }

        if ($status) {
            $queryParamsMap[] = ['key' => 'status', 'value' => $status];
        }

        if ($sort) {
            $queryParamsMap[] = ['key' => 'sort', 'value' => $sort];
        }

        if ($after) {
            $queryParamsMap[] = ['key' => 'after', 'value' => $after];
        }

        if ($first) {
            $queryParamsMap[] = ['key' => 'first', 'value' => $first];
        }

        return $this->getApi('channel_points/custom_rewards/redemptions', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#create-custom-rewards
     */
    public function createCustomReward(string $bearer, string $broadcasterId, string $title, int $cost, $additionalBodyParams = []): ResponseInterface
    {
        // $additionalBodyParams should be a standard key => value format, eg. ['game_id' => '1'];
        $queryParamsMap = $bodyParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];

        $bodyParamsMap[] = ['key' => 'title', 'value' => $title];
        $bodyParamsMap[] = ['key' => 'cost', 'value' => $cost];

        foreach ($additionalBodyParams as $key => $value) {
            $bodyParamsMap[] = ['key' => $key, 'value' => $value];
        }

        return $this->postApi('channel_points/custom_rewards', $bearer, $queryParamsMap, $bodyParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#update-custom-reward
     */
    public function updateCustomReward(string $bearer, string $broadcasterId, string $rewardId, $bodyParams = []): ResponseInterface
    {
        // $bodyParams should be a standard key => value format, eg. ['game_id' => '1'];
        $queryParamsMap = $bodyParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];
        $queryParamsMap[] = ['key' => 'id', 'value' => $rewardId];

        foreach ($bodyParams as $key => $value) {
            $bodyParamsMap[] = ['key' => $key, 'value' => $value];
        }

        return $this->patchApi('channel_points/custom_rewards', $bearer, $queryParamsMap, $bodyParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#delete-custom-reward
     */
    public function deleteCustomReward(string $bearer, string $broadcasterId, string $id): ResponseInterface
    {
        $queryParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];

        $queryParamsMap[] = ['key' => 'id', 'value' => $id];

        return $this->deleteApi('channel_points/custom_rewards', $bearer, $queryParamsMap);
    }

    /**
     * @throws GuzzleException
     * @link https://dev.twitch.tv/docs/api/reference#update-redemption-status
     */
    public function updateRedemptionStatus(string $bearer, string $broadcasterId, string $rewardId, string $redemptionId, string $status): ResponseInterface
    {
        $queryParamsMap = $bodyParamsMap = [];

        $queryParamsMap[] = ['key' => 'broadcaster_id', 'value' => $broadcasterId];
        $queryParamsMap[] = ['key' => 'reward_id', 'value' => $rewardId];
        $queryParamsMap[] = ['key' => 'id', 'value' => $redemptionId];

        $bodyParamsMap[] = ['key' => 'status', 'value' => $status];

        return $this->patchApi('channel_points/custom_rewards/redemptions', $bearer, $queryParamsMap, $bodyParamsMap);
    }
}

<?php

namespace spec\NewTwitchApi;

use NewTwitchApi\RequestGenerator;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\Auth\OauthApi;
use NewTwitchApi\Resources\AdsApi;
use NewTwitchApi\Resources\AnalyticsApi;
use NewTwitchApi\Resources\BitsApi;
use NewTwitchApi\Resources\ChannelPointsApi;
use NewTwitchApi\Resources\ChannelsApi;
use NewTwitchApi\Resources\EntitlementsApi;
use NewTwitchApi\Resources\EventSubApi;
use NewTwitchApi\Resources\GamesApi;
use NewTwitchApi\Resources\ModerationApi;
use NewTwitchApi\Resources\PollsApi;
use NewTwitchApi\Resources\PredictionsApi;
use NewTwitchApi\Resources\ScheduleApi;
use NewTwitchApi\Resources\StreamsApi;
use NewTwitchApi\Resources\SubscriptionsApi;
use NewTwitchApi\Resources\TagsApi;
use NewTwitchApi\Resources\TeamsApi;
use NewTwitchApi\Resources\UsersApi;
use NewTwitchApi\Resources\VideosApi;
use NewTwitchApi\Resources\WebhooksApi;
use NewTwitchApi\Webhooks\WebhooksSubscriptionApi;
use PhpSpec\ObjectBehavior;

class NewTwitchApiSpec extends ObjectBehavior
{
    function let(HelixGuzzleClient $guzzleClient)
    {
        $this->beConstructedWith($guzzleClient, 'client-id', 'client-secret');
    }

    function it_should_provide_oauth_api()
    {
        $this->getOauthApi()->shouldBeAnInstanceOf(OauthApi::class);
    }

    function it_should_provide_ads_api()
    {
        $this->getAdsApi()->shouldBeAnInstanceOf(AdsApi::class);
    }

    function it_should_provide_analytics_api()
    {
        $this->getAnalyticsApi()->shouldBeAnInstanceOf(AnalyticsApi::class);
    }

    function it_should_provide_bits_api()
    {
        $this->getBitsApi()->shouldBeAnInstanceOf(BitsApi::class);
    }

    function it_should_provide_channel_points_api()
    {
        $this->getChannelPointsApi()->shouldBeAnInstanceOf(ChannelPointsApi::class);
    }

    function it_should_provide_channels_api()
    {
        $this->getChannelsApi()->shouldBeAnInstanceOf(ChannelsApi::class);
    }

    function it_should_provide_entitlements_api()
    {
        $this->getEntitlementsApi()->shouldBeAnInstanceOf(EntitlementsApi::class);
    }

    function it_should_provide_event_sub_api()
    {
        $this->getEventSubApi()->shouldBeAnInstanceOf(EventSubApi::class);
    }

    function it_should_provide_games_api()
    {
        $this->getGamesApi()->shouldBeAnInstanceOf(GamesApi::class);
    }

    function it_should_provide_polls_api()
    {
        $this->getPollsApi()->shouldBeAnInstanceOf(PollsApi::class);
    }

    function it_should_provide_predictions_api()
    {
        $this->getPredictionsApi()->shouldBeAnInstanceOf(PredictionsApi::class);
    }

    function it_should_provide_schedule_api()
    {
        $this->getScheduleApi()->shouldBeAnInstanceOf(ScheduleApi::class);
    }

    function it_should_provide_subscriptions_api()
    {
        $this->getSubscriptionsApi()->shouldBeAnInstanceOf(SubscriptionsApi::class);
    }

    function it_should_provide_streams_api()
    {
        $this->getStreamsApi()->shouldBeAnInstanceOf(StreamsApi::class);
    }

    function it_should_provide_tags_api()
    {
        $this->getTagsApi()->shouldBeAnInstanceOf(TagsApi::class);
    }

    function it_should_provide_teams_api()
    {
        $this->getTeamsApi()->shouldBeAnInstanceOf(TeamsApi::class);
    }

    function it_should_provide_users_api()
    {
        $this->getUsersApi()->shouldBeAnInstanceOf(UsersApi::class);
    }

    function it_should_provide_videos_api()
    {
        $this->getVideosApi()->shouldBeAnInstanceOf(VideosApi::class);
    }

    function it_should_provide_webhooks_api()
    {
        $this->getWebhooksApi()->shouldBeAnInstanceOf(WebhooksApi::class);
    }

    function it_should_provide_webhooks_subscription_api()
    {
        $this->getWebhooksSubscriptionApi()->shouldBeAnInstanceOf(WebhooksSubscriptionApi::class);
    }
}

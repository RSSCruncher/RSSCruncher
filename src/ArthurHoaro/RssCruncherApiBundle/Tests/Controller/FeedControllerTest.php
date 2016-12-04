<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use Debril\RssAtomBundle\Exception\DriverUnreachableResourceException;
use Symfony\Bundle\FrameworkBundle\Client;

class FeedControllerTest extends ControllerTest
{

    /**
     * @var Client
     */
    protected $client;

    /**
     *
     */
    public function setUp()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    /**
     *
     */
    public function testJsonGetFeedAction()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];

        $route =  $this->getUrl('api_1_get_feed', array('id' => $feed->getId(), '_format' => 'json'));

        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals($feed->getId(), $decoded['id']);

    }

    public function testJsonGetFeedsAction()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::NOT_PARSABLE];

        $route =  $this->getUrl('api_1_get_feeds');
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(($decoded != null && $decoded != false), 'JSON invalid format: '. $content);
        $this->assertEquals(3, count($decoded), 'Number of results is invalid');
        $this->assertEquals($feed->getId(), $decoded[0]['id']);
    }

/*
    public function testJsonPostFeedAction()
    {
        $route =  $this->getUrl('api_1_post_feed', array('_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sitename":"sitename1","siteurl":"siteurl1","feedname":"feedname1","feedurl":"feedurl1"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostFeedActionShouldReturn400WithBadParameters()
    {
        $route =  $this->getUrl('api_1_post_feed', array('_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"siteurl":"siteurl2","feedname":"feedname2","feedurl":"feedurl2"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    public function testJsonPutNewAndUpdateFeedAction()
    {
        $route =  $this->getUrl('api_1_put_feed', array('id' => 666, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sitename":"whatever", "siteurl":"http://whatever.com","feedname":"whatever","feedurl":"http://whatever.com/?rss"}'
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201, false);

        $location = explode('/', $response->headers->get('location'));
        $newId = end($location);
        $route =  $this->getUrl('api_1_put_feed', array('id' => $newId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sitename":"whatever2", "siteurl":"http://whatever.com","feedname":"whatever","feedurl":"http://whatever.com/?movedrss"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);
    }

    public function testJsonPatchArticleAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feed = array_pop($feeds);

        $route =  $this->getUrl('api_1_patch_feed', array('id' => $feed->getId(), '_format' => 'json'));

        $this->client->request(
            'PATCH',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"feedurl":"http://whatever.com/?NEWRSS"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);
    }

    public function testJsonBasicDoubleRefreshFeedAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::VALID]->getId();

        $route =  $this->getUrl('api_1_get_feed_refresh', array('id' => $feedId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PATCH',
            $route,
            array('ACCEPT' => 'application/json'));

        $this->assertJsonResponse($this->client->getResponse(), 200, true);

        $this->client->request(
            'PATCH',
            $route,
            array('ACCEPT' => 'application/json'));

        $this->assertJsonResponse($this->client->getResponse(), 200, true);
    }

    public function testJsonRefreshNotExistingFeed()
    {
        $feedId = -1;
        $route =  $this->getUrl('api_1_get_feed_refresh', array('id' => $feedId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PATCH',
            $route,
            array('ACCEPT' => 'application/json'));

        $this->assertJsonResponse($this->client->getResponse(), 404, true);
    }

    public function testJsonRefreshUnreachableFeed()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::DUMMY]->getId();

        $route =  $this->getUrl('api_1_get_feed_refresh', array('id' => $feedId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PATCH',
            $route,
            array('ACCEPT' => 'application/json'));

        $this->assertJsonResponseException(
            $this->client->getResponse(),
            500, true, 'application/json',
            get_class(new DriverUnreachableResourceException())
        );
    }

    public function testJsonRefreshNotParsableFeed()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::NOT_PARSABLE]->getId();

        $route =  $this->getUrl('api_1_get_feed_refresh', array('id' => $feedId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PATCH',
            $route,
            array('ACCEPT' => 'application/json'));

        $this->assertJsonResponseException(
            $this->client->getResponse(),
            500, true, 'application/json',
            get_class(new FeedNotParsedException())
        );
    }

    public function testJsonDisableThenTryToAccessAndRefreshFeed() {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);

        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::VALID]->getId();

        $route = $this->getUrl('api_1_patch_feed', array('id' => $feedId, '_format' => 'json'));
        $this->client->request(
            'PATCH',
            $route,
            [],
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"enabled": false}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);

        $route =  $this->getUrl('api_1_get_feed', array('id' => $feedId, '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $this->assertJsonResponse($this->client->getResponse(), 200);

        $route = $this->getUrl('api_1_get_feed_refresh', array('id' => $feedId, '_format' => 'json'));
        $this->client->request('PATCH', $route, array('ACCEPT' => 'application/json'));
        $this->assertJsonResponse($this->client->getResponse(), 200);
    }*/
}

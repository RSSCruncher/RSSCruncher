<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleDTO;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class FeedControllerTest
 * @package ArthurHoaro\RssCruncherApiBundle\Tests\Controller
 */
class FeedControllerTest extends ControllerTest
{
    /**
     * Number of fields in a feed.
     */
    const NB_FEED_FIELDS = 6;

    /**
     * Number of fields in an article.
     */
    const NB_ARTICLE_FIELDS = 7;

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
     * Retrieve a single existing (user) feed by its ID.
     */
    public function testJsonGetFeedAction()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];

        $route =  $this->getUrl('api_1_get_feed', ['id' => $feed->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        // Number of fields.
        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($feed->getId(), $decoded['id']);
        $this->assertEquals($feed->getSiteName(), $decoded['site_name']);
        $this->assertEquals($feed->getSiteUrl(), $decoded['site_url']);
        $this->assertEquals($feed->getFeedName(), $decoded['feed_name']);
        $this->assertEquals('https://'. $feed->getFeed()->getFeedUrl(), $decoded['feed_url']);
    }

    /**
     * Retrieve a feed that doesn't exist.
     */
    public function testGetFeed404()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $route =  $this->getUrl('api_1_get_feed', ['id' => $feed->getId() - 1, '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    /**
     * Retrieve a feed that which is own by another user.
     */
    public function testGetFeedOtherUser()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::OTHER_USER];
        $route =  $this->getUrl('api_1_get_feed', ['id' => $feed->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    /**
     * Retrieve a list of feeds.
     */
    public function testJsonGetFeedsAction()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::NOT_PARSABLE];

        $route =  $this->getUrl('api_1_get_feeds');
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(($decoded != null && $decoded != false), 'JSON invalid format: '. $content);
        $this->assertEquals(3, count($decoded), 'Number of results is invalid');

        $first = $decoded[0];
        $this->assertEquals(self::NB_FEED_FIELDS, count($first));
        $this->assertEquals($feed->getId(), $first['id']);
        $this->assertEquals($feed->getSiteName(), $first['site_name']);
        $this->assertEquals($feed->getSiteUrl(), $first['site_url']);
        $this->assertEquals($feed->getFeedName(), $first['feed_name']);
        $this->assertEquals('http://'. $feed->getFeed()->getFeedUrl(), $first['feed_url']);

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::VALID];
        $this->assertEquals($feed->getId(), $decoded[1]['id']);
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $this->assertEquals($feed->getId(), $decoded[2]['id']);
    }

    /**
     * Retrieve a list of links with limit and offset parameters.
     */
    public function testGetFeedsLimitOffset()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::VALID];

        $route =  $this->getUrl('api_1_get_feeds');
        $this->client->request('GET', $route, [
            'limit' => 1,
            'offset' => 1,
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(($decoded != null && $decoded != false), 'JSON invalid format: '. $content);
        $this->assertEquals(1, count($decoded), 'Number of results is invalid');

        $first = $decoded[0];
        $this->assertEquals(self::NB_FEED_FIELDS, count($first));
        $this->assertEquals($feed->getId(), $first['id']);
        $this->assertEquals($feed->getSiteName(), $first['site_name']);
        $this->assertEquals($feed->getSiteUrl(), $first['site_url']);
        $this->assertEquals($feed->getFeedName(), $first['feed_name']);
        $this->assertEquals('http://'. $feed->getFeed()->getFeedUrl(), $first['feed_url']);
    }

    /**
     * Retrieve a list of links with invalid limit and offset parameters.
     *  -> They're ignored and default values are used.
     */
    public function testGetFeedsLimitOffsetInvalid()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::NOT_PARSABLE];

        $route =  $this->getUrl('api_1_get_feeds');
        $this->client->request('GET', $route, [
            'limit' => -1,
            'offset' => 'bla',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(($decoded != null && $decoded != false), 'JSON invalid format: '. $content);
        $this->assertEquals(3, count($decoded), 'Number of results is invalid');

        $first = $decoded[0];
        $this->assertEquals(self::NB_FEED_FIELDS, count($first));
        $this->assertEquals($feed->getId(), $first['id']);
        $this->assertEquals($feed->getSiteName(), $first['site_name']);
        $this->assertEquals($feed->getSiteUrl(), $first['site_url']);
        $this->assertEquals($feed->getFeedName(), $first['feed_name']);
        $this->assertEquals('http://'. $feed->getFeed()->getFeedUrl(), $first['feed_url']);

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::VALID];
        $this->assertEquals($feed->getId(), $decoded[1]['id']);
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $this->assertEquals($feed->getId(), $decoded[2]['id']);
    }

    /**
     * Create a new feed (return code 201)
     * Then try to create a duplicate (return code 409).
     */
    public function testJsonPostFeedAction()
    {
        $data = [
            'site_name' => 'sitename1',
            'site_url' => 'http://sitename1.tld',
            'feed_name' => 'feedname1',
            'feed_url' => 'http://sitename1.tld/feed',
        ];

        $route = $this->getUrl('api_1_post_feed', ['_format' => 'json']);
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $id = $decoded['id'];
        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertTrue(is_int($id));
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($data['site_url'], $decoded['site_url']);
        $this->assertEquals($data['feed_name'], $decoded['feed_name']);
        $this->assertEquals($data['feed_url'], $decoded['feed_url']);

        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 409);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($id, $decoded['id']);
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($data['site_url'], $decoded['site_url']);
        $this->assertEquals($data['feed_name'], $decoded['feed_name']);
        $this->assertEquals($data['feed_url'], $decoded['feed_url']);
    }

    /**
     * Test POST feed with bad parameters.
     */
    public function testJsonPostFeedActionShouldReturn400WithBadParameters()
    {
        // FeedName missing
        $data = [
            'site_name' => 'sitename1',
            'site_url' => 'http://sitename1.tld',
        ];
        $route =  $this->getUrl('api_1_post_feed', ['_format' => 'json']);
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    /**
     * Test POST feed existing for another user:
     *  - new UserFeed
     *  - same Feed
     *  - transparent for the user
     */
    public function testPostFeedExistingAnotherUser()
    {
        // FeedName missing
        $data = [
            'site_name' => 'sitename1',
            'site_url' => 'http://sitename1.tld',
            'feed_name' => 'feedname1',
            'feed_url' => 'http://sitename1.tld/feed',
        ];
        $route =  $this->getUrl('api_1_post_feed', ['_format' => 'json']);
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    /**
     * Update a user feed with PUT (existing Feed and UserFeed).
     */
    public function testPutFeedExisting()
    {
        $data = [
            'site_name' => 'whatever',
            'site_url' => 'http://whatever.com',
            'feed_name' => 'whatever',
            'feed_url' => 'https://feedUrl.tld/rss',
        ];

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $route =  $this->getUrl('api_1_put_feed', ['id' => $feed->getId(), '_format' => 'json']);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($feed->getId(), $decoded['id']);
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($data['site_url'], $decoded['site_url']);
        $this->assertEquals($data['feed_name'], $decoded['feed_name']);
        $this->assertEquals($data['feed_url'], $decoded['feed_url']);
    }

    /**
     * Update a user feed with PUT (existing Feed and UserFeed).
     */
    public function testPutFeedExistingPartial()
    {
        $data = [
            'site_name' => 'whatever',
            'site_url' => 'http://whatever.com',
            //'feed_name' => 'whatever',
            'feed_url' => 'https://feedUrl.tld/rss',
        ];

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $route =  $this->getUrl('api_1_put_feed', ['id' => $feed->getId(), '_format' => 'json']);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($feed->getId(), $decoded['id']);
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($data['site_url'], $decoded['site_url']);
        $this->assertEmpty($decoded['feed_name']);
        $this->assertEquals($data['feed_url'], $decoded['feed_url']);
    }

    /**
     * Update a user feed with PUT (existing UserFeed and new Feed - feed URL).
     */
    public function testPutUserFeedExistingNewFeed()
    {
        $data = [
            'site_name' => 'whatever',
            'site_url' => 'http://whatever.com',
            'feed_name' => 'whatever',
            'feed_url' => 'https://newfeed.tld/rss',
        ];

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $route =  $this->getUrl('api_1_put_feed', ['id' => $feed->getId(), '_format' => 'json']);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($feed->getId(), $decoded['id']);
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($data['site_url'], $decoded['site_url']);
        $this->assertEquals($data['feed_name'], $decoded['feed_name']);
        $this->assertEquals($data['feed_url'], $decoded['feed_url']);
    }

    /**
     * Update a user feed with PUT (existing UserFeed and new Feed - feed URL).
     */
    public function testPutFeed404()
    {
        $data = [
            'site_name' => 'whatever',
            'site_url' => 'http://whatever.com',
            'feed_name' => 'whatever',
            'feed_url' => 'https://newfeed.tld/rss',
        ];

        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        $route =  $this->getUrl('api_1_put_feed', ['id' => $feed->getId() - 1, '_format' => 'json']);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404, false);
    }

    /**
     * Patch a single field item
     *
     * TODO: patch feed URL
     */
    public function testJsonPatchFeedAction()
    {
        $data = [
            'site_name' => 'patched siteName',
        ];
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];

        $route =  $this->getUrl('api_1_patch_feed', ['id' => $feed->getId(), '_format' => 'json']);
        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertEquals(self::NB_FEED_FIELDS, count($decoded));
        $this->assertEquals($feed->getId(), $decoded['id']);
        $this->assertEquals($data['site_name'], $decoded['site_name']);
        $this->assertEquals($feed->getSiteUrl(), $decoded['site_url']);
        $this->assertEquals($feed->getFeedName(), $decoded['feed_name']);
        $this->assertEquals('https://'. $feed->getFeed()->getFeedUrl(), $decoded['feed_url']);
    }

    /**
     * Test refresh feed - should retrieve the latest article.
     * Then re-refresh -> no new article.
     *
     * FIXME! Should not rely on external resource.
     * FIXME! Should try new article/edit + refresh.
     */
    public function testJsonBasicRefreshFeedAction()
    {
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::VALID]->getId();

        $route =  $this->getUrl('api_1_get_feed_refresh', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertEquals(11, count($decoded));
        $item = $decoded[0];
        $this->assertEquals(self::NB_ARTICLE_FIELDS, count($item));
        $this->assertTrue(is_int($item['id']));
        $this->assertEquals('http://hoa.ro/chez-mozilla-la-securite-avant-la-liberte', $item['public_id']);
        $this->assertEquals('Chez Mozilla, la sécurité avant la liberté', $item['title']);
        $this->assertEquals('http://hoa.ro/chez-mozilla-la-securite-avant-la-liberte', $item['link']);
        $this->assertEquals('2015-02-12T10:05:00+0100', $item['publication_date']);
        $this->assertContains('<p>Le 10 février, Mozilla <a', $item['content']);
        $this->assertNotContains('CDATA', $item['content']);
        $this->assertEquals($feedId, $item['feed']['id']);

        $this->client->request('GET', $route, [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(0, count($decoded));
    }

    /**
     * Test refresh on a not existing feed => 404
     */
    public function testJsonRefreshNotExistingFeed()
    {
        $feedId = -1;
        $route = $this->getUrl('api_1_get_feed_refresh', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 404, true);
    }

    /**
     * Try refreshing an invalid feed =>
     */
    public function testJsonRefreshUnreachableFeed()
    {
        $feedId = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY]->getId();

        $route =  $this->getUrl('api_1_get_feed_refresh', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->assertJsonResponseException(
            $this->client->getResponse(),
            500,
            true,
            'application/json',
            \Exception::class
        );
    }

    public function testDisableFeedThen404()
    {
        $feedId = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY]->getId();

        $route =  $this->getUrl('api_1_delete_feed', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('DELETE', $route, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);

        $route =  $this->getUrl('api_1_get_feed', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 404, false, false);
    }

    /**
     * FIXME! marche pas
     */
    public function testDisableFeedThenGet404ThenRefresh404() {
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::VALID]->getId();

        $route =  $this->getUrl('api_1_delete_feed', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('DELETE', $route, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);

        $route =  $this->getUrl('api_1_get_feed', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 404);

        $route = $this->getUrl('api_1_get_feed_refresh', ['id' => $feedId, '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $this->assertJsonResponse($this->client->getResponse(), 404);
    }
}

<?php
/**
 * FeedControllerTest.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Controller;

use ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadFeedData;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

class FeedControllerTest extends WebTestCase {

    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        );

        $this->client = static::createClient(array(), $this->auth);
    }

    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testJsonGetFeedAction()
    {
        $fixtures = array('ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadFeedData');
        $this->customSetUp($fixtures);
        $feeds = LoadFeedData::$feeds;
        $feed = array_pop($feeds);

        $route =  $this->getUrl('api_1_get_feed', array('id' => $feed->getId(), '_format' => 'json'));

        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));

    }


    public function testJsonPostFeedAction()
    {
        $this->setUp();
        $this->client->request(
            'POST',
            '/api/v1/feeds.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sitename":"sitename1","siteurl":"siteurl1","feedname":"feedname1","feedurl":"feedurl1"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostFeedActionShouldReturn400WithBadParameters()
    {
        $this->setUp();
        $this->client->request(
            'POST',
            '/api/v1/feeds.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"siteurl":"siteurl2","feedname":"feedname2","feedurl":"feedurl2"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }


    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
} 
<?php
/**
 * ArticleControllerTest.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Controller;

use ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadArticleData;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

class ArticleControllerTest extends WebTestCase {

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

    public function testJsonGetArticleAction()
    {
        $fixtures = array('ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadArticleData');
        $this->customSetUp($fixtures);
        $articles = LoadArticleData::$articles;
        $article = array_pop($articles);

        $route =  $this->getUrl('api_1_get_article', array('id' => $article->getId(), '_format' => 'json'));

        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(!empty($decoded['id']));

    }


    public function testJsonPostArticleAction()
    {
        $fixtures = array('ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadArticleData');
        $this->customSetUp($fixtures);
        $feeds = LoadArticleData::$feeds;
        $feedId = $feeds[0]->getId();

        $this->setUp();
        $this->client->request(
            'POST',
            '/api/v1/articles.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"titre32","link":"http://blogofthedeath.com/article32","feed": '. $feedId .',
                "publicationdate": {
                    "date": { "year": 2014, "month": 11, "day": 5 },
                    "time": { "hour": 23, "minute": 11 }
                }
            }'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostArticleWithoutFeedAction()
    {
        $this->setUp();
        $this->client->request(
            'POST',
            '/api/v1/articles.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"titre32","link":"http://blogofthedeath.com/article32",
                "publicationdate": {
                    "date": { "year": 2014, "month": 11, "day": 5 },
                    "time": { "hour": 23, "minute": 11 }
                }
            }'
        );

        $this->assertJsonResponse($this->client->getResponse(), 500, false);
    }

    public function testJsonPostArticleActionShouldReturn400WithBadParameters()
    {
        $this->setUp();
        $this->client->request(
            'POST',
            '/api/v1/articles.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"siteurl":"siteurl2","articlename":"articlename2","articleurl":"articleurl2"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    public function testJsonRefreshFeedAction()
    {
        $fixtures = array('ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity\LoadArticleData');
        $this->customSetUp($fixtures);
        $feeds = LoadArticleData::$feeds;
        $feedId = $feeds[1]->getId();

        $this->setUp();
        $this->client->request(
            'PATCH',
            '/api/v1/feeds/'. $feedId .'/refresh',
            array('ACCEPT' => 'application/json'));

        var_dump($feeds);
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
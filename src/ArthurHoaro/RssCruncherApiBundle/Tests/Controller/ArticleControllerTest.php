<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;

class ArticleControllerTest extends ControllerTest {

    /*protected $client;

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
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $articles = LoadBasicFeedsArticlesData::$articles;
        $article = array_pop($articles);

        $route =  $this->getUrl('api_1_get_article', array('id' => $article->getId(), '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decodedArticle = json_decode($content, true);
        $this->assertTrue(($decodedArticle != null && $decodedArticle != false), 'JSON invalid format');
        $this->assertTrue(!empty($decodedArticle['id']) && $decodedArticle['id'] == $article->getId());
    }

    public function testJsonGetArticlesAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $articles = LoadBasicFeedsArticlesData::$articles;

        $offset = 1; $limit = 2;
        $route =  $this->getUrl('api_1_get_articles', array('offset' => $offset, 'limit' => $limit));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);

        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(($decoded != null && $decoded != false), 'JSON invalid format');
        $this->assertTrue(count($decoded) == $limit, 'Number of results is invalid');
        $this->assertTrue(!empty($decoded[0]['id']) && $decoded[0]['id'] == $articles[count($articles) - 1 - $offset]->getId());

    }

    public function testJsonGetNotFoundArticleAction()
    {
        $route =  $this->getUrl('api_1_get_article', array('id' => -1, '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));

        $this->assertJsonResponse($this->client->getResponse(), 404);
    }


    public function testJsonPostArticleAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::DUMMY]->getId();

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

        $this->assertJsonResponse($this->client->getResponse(), 500, true);
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

        $this->assertJsonResponse($this->client->getResponse(), 400, true);
    }

    public function testJsonPutNewAndUpdateArticleAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $feeds = LoadBasicFeedsArticlesData::$feeds;
        $feedId = $feeds[LoadArticleFeedArray::DUMMY]->getId();

        $route =  $this->getUrl('api_1_put_article', array('id' => 666, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"titre42","link":"http://blogofthedeath.com/article42","feed": '. $feedId .',
                "content": "hello world",
                "publicationdate": {
                    "date": { "year": 2014, "month": 11, "day": 5 },
                    "time": { "hour": 23, "minute": 11 }
                }
            }'
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201, false);

        $location = explode('/', $response->headers->get('location'));
        $newId = end($location);
        $route =  $this->getUrl('api_1_put_article', array('id' => $newId, '_format' => 'json'));

        $this->setUp();
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"titre42","link":"http://blogofthedeath.com/article42","feed": '. $feedId .',
                "content": "the world has been updated",
                "publicationdate": {
                    "date": { "year": 2014, "month": 11, "day": 5 },
                    "time": { "hour": 23, "minute": 11 }
                }
            }'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);
    }

    public function testJsonPatchArticleAction()
    {
        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->customSetUp($fixtures);
        $articles = LoadBasicFeedsArticlesData::$articles;
        $article = array_pop($articles);

        $route =  $this->getUrl('api_1_patch_article', array('id' => $article->getId(), '_format' => 'json'));

        $this->client->request(
            'PATCH',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"content": "a whole new unicorn"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false, false);
    }*/
} 
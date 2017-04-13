<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;
use Symfony\Component\Validator\Constraints\DateTime;

class ArticleControllerTest extends ControllerTest
{
    /**
     * Number of fields in an article.
     */
    const NB_FIELDS_ARTICLE = 9;
    const NB_FIELDS_ARTICLE_CONTENT = 3;

    /**
     * Number of fields in an article.
     */
    const NB_ARTICLES = 3;

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

    public function testGetCArticles()
    {
        $articleRef = LoadBasicFeedsArticlesData::$articles[0];
        $feedRef = LoadBasicFeedsArticlesData::$feeds[0];

        $route =  $this->getUrl('api_1_get_articles');
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals(self::NB_ARTICLES, count($decoded));

        $article = $decoded[0];
        $this->assertEquals(FeedControllerTest::NB_ARTICLE_FIELDS, count($article));
        $this->assertEquals($articleRef->getId(), $article['id']);
        $this->assertEquals($articleRef->getPublicId(), $article['public_id']);
        $this->assertEquals($articleRef->getTitle(), $article['title']);
        $this->assertEquals($articleRef->getLink(), $article['link']);
        $this->assertEquals($articleRef->getLastArticleContent()->getContent(), $article['content']);
        $this->assertEquals($articleRef->getPublicationDate()->format(\DateTime::ISO8601), $article['publication_date']);
        if (! empty($articleRef->getModificationDate())) {
            $this->assertEquals($articleRef->getModificationDate()->format(\DateTime::ISO8601), $article['modification_date']);
        } else {
            $this->assertEmpty($article['modification_date']);
        }
        $feed = $article['feed'];
        $this->assertEquals(FeedControllerTest::NB_FEED_FIELDS, count($feed));
        $this->assertEquals($feedRef->getId(), $feed['id']);
        $this->assertEquals($feedRef->getSiteName(), $feed['site_name']);
        $this->assertEquals($feedRef->getSiteUrl(), $feed['site_url']);
        $this->assertEquals($feedRef->getFeedName(), $feed['feed_name']);
        $this->assertEquals('https://'. $feedRef->getFeed()->getFeedUrl(), $feed['feed_url']);
        $this->assertEquals($feedRef->getCategory()->getName(), $feed['category']);
    }

    public function testGetCArticlesLimitOffset()
    {
        $articleRef = LoadBasicFeedsArticlesData::$articles[1];
        $feedRef = LoadBasicFeedsArticlesData::$feeds[0];

        $route =  $this->getUrl('api_1_get_articles');
        $this->client->request('GET', $route, [
            'limit' => '1',
            'offset' => '1',
            'ACCEPT' => 'application/json',
        ]);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals(1, count($decoded));

        $article = $decoded[0];
        $this->assertEquals(FeedControllerTest::NB_ARTICLE_FIELDS, count($article));
        $this->assertEquals($articleRef->getId(), $article['id']);
        $this->assertEquals($articleRef->getPublicId(), $article['public_id']);
        $this->assertEquals($articleRef->getTitle(), $article['title']);
        $this->assertEquals($articleRef->getLink(), $article['link']);
        if (! empty($articleRef->getLastArticleContent())) {
            $this->assertEquals($articleRef->getLastArticleContent()->getContent(), $article['content']);
            $this->assertContains('new', $article['content']);
        } else {
            $this->assertEquals('', $article['content']);
        }
        $this->assertEquals($articleRef->getPublicationDate()->format(\DateTime::ISO8601), $article['publication_date']);
        if (! empty($articleRef->getModificationDate())) {
            $this->assertEquals($articleRef->getModificationDate()->format(\DateTime::ISO8601), $article['modification_date']);
        } else {
            $this->assertEmpty($article['modification_date']);
        }
        $feed = $article['feed'];
        $this->assertEquals(FeedControllerTest::NB_FEED_FIELDS, count($feed));
        $this->assertEquals($feedRef->getId(), $feed['id']);
        $this->assertEquals($feedRef->getSiteName(), $feed['site_name']);
        $this->assertEquals($feedRef->getSiteUrl(), $feed['site_url']);
        $this->assertEquals($feedRef->getFeedName(), $feed['feed_name']);
        $this->assertEquals('https://'. $feedRef->getFeed()->getFeedUrl(), $feed['feed_url']);
        $this->assertEquals($feedRef->getCategory()->getName(), $feed['category']);
    }

    public function testGetCArticle()
    {
        $articleRef = LoadBasicFeedsArticlesData::$articles[2];
        $feedRef = LoadBasicFeedsArticlesData::$feeds[0];

        $route =  $this->getUrl('api_1_get_article', ['id' => $articleRef->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $article = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_ARTICLE, count($article));

        $this->assertEquals(FeedControllerTest::NB_ARTICLE_FIELDS, count($article));
        $this->assertEquals($articleRef->getId(), $article['id']);
        $this->assertEquals($articleRef->getPublicId(), $article['public_id']);
        $this->assertEquals($articleRef->getTitle(), $article['title']);
        $this->assertEquals($articleRef->getLink(), $article['link']);
        if (! empty($articleRef->getLastArticleContent())) {
            $this->assertEquals($articleRef->getLastArticleContent()->getContent(), $article['content']);
        } else {
            $this->assertEquals('', $article['content']);
        }
        $this->assertEquals($articleRef->getPublicationDate()->format(\DateTime::ISO8601), $article['publication_date']);
        if (! empty($articleRef->getModificationDate())) {
            $this->assertEquals($articleRef->getModificationDate()->format(\DateTime::ISO8601), $article['modification_date']);
        } else {
            $this->assertEmpty($article['modification_date']);
        }
        $feed = $article['feed'];
        $this->assertEquals(FeedControllerTest::NB_FEED_FIELDS, count($feed));
        $this->assertEquals($feedRef->getId(), $feed['id']);
        $this->assertEquals($feedRef->getSiteName(), $feed['site_name']);
        $this->assertEquals($feedRef->getSiteUrl(), $feed['site_url']);
        $this->assertEquals($feedRef->getFeedName(), $feed['feed_name']);
        $this->assertEquals('https://'. $feedRef->getFeed()->getFeedUrl(), $feed['feed_url']);
        $this->assertEquals($feedRef->getCategory()->getName(), $feed['category']);
    }

    public function testGetArticle404()
    {
        $route =  $this->getUrl('api_1_get_article', ['id' => -1]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testGetArticleHistory()
    {
        $articleRef = LoadBasicFeedsArticlesData::$articles[1];

        $route =  $this->getUrl('api_1_get_article_history', ['id' => $articleRef->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals(2, count($decoded));
        $latest = $decoded[0];
        $this->assertEquals(self::NB_FIELDS_ARTICLE_CONTENT, count($latest));
        $this->assertEquals('Article2 new content...', $latest['content']);

        $previous = $decoded[1];
        $this->assertEquals('Article2 first content...', $previous['content']);
        $this->assertTrue(
            \DateTime::createFromFormat(\DateTime::ATOM, $previous['date'])
            <
            \DateTime::createFromFormat(\DateTime::ATOM, $latest['date'])
        );
    }

    public function testGetArticleHistoryOffsetLimit()
    {
        $articleRef = LoadBasicFeedsArticlesData::$articles[1];

        $route =  $this->getUrl('api_1_get_article_history', ['id' => $articleRef->getId()]);
        $this->client->request('GET', $route, [
            'limit' => 1,
            'offset' => 1,
            'ACCEPT' => 'application/json'
        ]);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals(1, count($decoded));
        $previous = $decoded[0];
        $this->assertEquals(self::NB_FIELDS_ARTICLE_CONTENT, count($previous));
        $this->assertEquals('Article2 first content...', $previous['content']);
    }

    public function testPostArticleRead()
    {
        
    }
}

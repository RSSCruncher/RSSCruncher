<?php


namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use Symfony\Bundle\FrameworkBundle\Client;

class FeedCategoryControllerTest extends ControllerTest
{
    /**
     * Number of fields in a feed.
     */
    const NB_FIELDS_CATEGORY = 4;

    /**
     * Number of fields in an article.
     */
    const NB_CATEGORIES = 3;

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

    public function testGetCategories()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[0];

        $route =  $this->getUrl('api_1_get_categories');
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertEquals(self::NB_CATEGORIES, count($decoded));

        $cat = $decoded[0];
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        if (! empty($ref->getDateModification())) {
            $this->assertEquals($ref->getDateModification()->format(\DateTime::ISO8601), $cat['updated']);
        } else {
            $this->assertEmpty($cat['updated']);
        }
    }

    public function testGetCategoriesLimitOffset()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[1];

        $route =  $this->getUrl('api_1_get_categories');
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

        $cat = $decoded[0];
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        if (! empty($ref->getDateModification())) {
            $this->assertEquals($ref->getDateModification()->format(\DateTime::ISO8601), $cat['updated']);
        } else {
            $this->assertEmpty($cat['updated']);
        }
    }

    public function testGetCategory()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_get_category', ['id' => $ref->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        if (! empty($ref->getDateModification())) {
            $this->assertEquals($ref->getDateModification()->format(\DateTime::ISO8601), $cat['updated']);
        } else {
            $this->assertEmpty($cat['updated']);
        }

        $route =  $this->getUrl('api_1_get_category', ['id' => $ref->getName()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        if (! empty($ref->getDateModification())) {
            $this->assertEquals($ref->getDateModification()->format(\DateTime::ISO8601), $cat['updated']);
        } else {
            $this->assertEmpty($cat['updated']);
        }
    }

    public function testGetCategory404()
    {
        $route =  $this->getUrl('api_1_get_category', ['id' => -1]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);

        $route =  $this->getUrl('api_1_get_category', ['id' => 'nope nope nope']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testPostCategory()
    {
        $data = ['name' => 'cat name 1'];

        $route =  $this->getUrl('api_1_post_category');
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertTrue(is_int($cat['id']));
        $this->assertEquals($data['name'], $cat['name']);
        $this->assertTrue((new \DateTime('-1sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['created']));
        $this->assertEmpty($cat['updated']);
    }

    public function testPostExistingCategory()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[2];
        $data = ['name' => $ref->getName()];

        $route =  $this->getUrl('api_1_post_category');
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 409);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        if (! empty($ref->getDateModification())) {
            $this->assertEquals($ref->getDateModification()->format(\DateTime::ISO8601), $cat['updated']);
        } else {
            $this->assertEmpty($cat['updated']);
        }
    }

    public function testPostAdditionalOrMissingFieldCategory()
    {
        $data = [];

        $route =  $this->getUrl('api_1_post_category');
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('should not be null', $decoded['children']['name']['errors'][0]);

        $data = ['name' => ''];

        $route =  $this->getUrl('api_1_post_category');
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('not be blank', $decoded['children']['name']['errors'][1]);

        $data = ['test' => 'test'];

        $route =  $this->getUrl('api_1_post_category');
        $this->client->request('POST', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('extra fields', $decoded['errors'][0]);
    }

    public function testPutCategory()
    {
        $data = ['name' => 'renamed'];
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_put_category', ['id' => $ref->getId()]);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($data['name'], $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        $this->assertTrue((new \DateTime('-2sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['updated']));

        $route =  $this->getUrl('api_1_put_category', ['id' => $data['name']]);
        $data = ['name' => 'renamed again'];
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($data['name'], $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        $this->assertTrue((new \DateTime('-1sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['updated']));
    }

    public function testPutCategory404()
    {
        $data = ['name' => 'renamed'];

        $route =  $this->getUrl('api_1_put_category', ['id' => -1]);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testPutAdditionalOrMissingFieldCategory()
    {
        $data = [];
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_put_category', ['id' => $ref->getId()]);
        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('should not be null', $decoded['children']['name']['errors'][0]);

        $data = ['name' => ''];

        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('not be blank', $decoded['children']['name']['errors'][1]);

        $data = ['test' => 'test'];

        $this->client->request('PUT', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('extra fields', $decoded['errors'][0]);
    }

    public function testPatchCategory()
    {
        $data = ['name' => 'renamed'];
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_patch_category', ['id' => $ref->getId()]);
        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($data['name'], $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        $this->assertTrue((new \DateTime('-1sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['updated']));

        $route =  $this->getUrl('api_1_patch_category', ['id' => $data['name']]);
        $data = ['name' => 'renamed again'];
        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($data['name'], $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        $this->assertTrue((new \DateTime('-1sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['updated']));
    }

    public function testPatchEmpty()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_patch_category', ['id' => $ref->getId()]);
        $this->client->request('PATCH', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(self::NB_FIELDS_CATEGORY, count($cat));
        $this->assertEquals($ref->getId(), $cat['id']);
        $this->assertEquals($ref->getName(), $cat['name']);
        $this->assertEquals($ref->getDateCreation()->format(\DateTime::ISO8601), $cat['created']);
        $this->assertTrue((new \DateTime('-1sec')) < \DateTime::createFromFormat(\DateTime::ISO8601, $cat['updated']));
    }

    public function testPatchCategory404()
    {
        $data = ['name' => 'renamed'];

        $route =  $this->getUrl('api_1_patch_category', ['id' => -1]);
        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testPatchAdditionalFieldCategory()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[2];
        $data = ['name' => ''];

        $route =  $this->getUrl('api_1_patch_category', ['id' => $ref->getId()]);

        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('not be blank', $decoded['children']['name']['errors'][1]);

        $data = ['test' => 'test'];

        $this->client->request('PATCH', $route, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertContains('extra fields', $decoded['errors'][0]);
    }

    public function testDeleteThenGetCategory()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[2];

        $route =  $this->getUrl('api_1_delete_category', ['id' => $ref->getId()]);
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 204, false, false);
        $this->assertEmpty($response->getContent());

        $route =  $this->getUrl('api_1_get_category', ['id' => $ref->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testDeleteCategory404()
    {
        $route =  $this->getUrl('api_1_delete_category', ['id' => -1]);
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testGetCategoryFeeds()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[0];
        $feedRef = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];

        $route =  $this->getUrl('api_1_get_category_feeds', ['id' => $ref->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(1, count($cat));
        $feed = $cat[0];
        $this->assertEquals(FeedControllerTest::NB_FEED_FIELDS, count($feed));
        $this->assertEquals($feedRef->getId(), $feed['id']);
        $this->assertEquals($feedRef->getSiteName(), $feed['site_name']);
        $this->assertEquals($feedRef->getSiteUrl(), $feed['site_url']);
        $this->assertEquals($feedRef->getFeedName(), $feed['feed_name']);
        $this->assertEquals('https://'. $feedRef->getFeed()->getFeedUrl(), $feed['feed_url']);
        $this->assertEquals($feedRef->getCategory()->getName(), $feed['category']);

        $route =  $this->getUrl('api_1_get_category_feeds', ['id' => $ref->getName()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $cat = json_decode($content, true);
        $this->assertEquals(1, count($cat));
        $feed = $cat[0];
        $this->assertEquals(FeedControllerTest::NB_FEED_FIELDS, count($feed));
        $this->assertEquals($feedRef->getId(), $feed['id']);
        $this->assertEquals($feedRef->getSiteName(), $feed['site_name']);
        $this->assertEquals($feedRef->getSiteUrl(), $feed['site_url']);
        $this->assertEquals($feedRef->getFeedName(), $feed['feed_name']);
        $this->assertEquals('https://'. $feedRef->getFeed()->getFeedUrl(), $feed['feed_url']);
        $this->assertEquals($feedRef->getCategory()->getName(), $feed['category']);
    }

    public function testGetCategoryFeeds404()
    {
        $route =  $this->getUrl('api_1_get_category_feeds', ['id' => -1]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);

        $route =  $this->getUrl('api_1_get_category_feeds', ['id' => 'nope']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertEquals(404, $decoded['error']['code']);
        $this->assertEquals('Not Found', $decoded['error']['message']);
    }

    public function testGetCategoryArticles()
    {
        $ref = LoadBasicFeedsArticlesData::$categories[0];
        $articleRef = LoadBasicFeedsArticlesData::$articles[0];
        $feedRef = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];

        $route =  $this->getUrl('api_1_get_category_articles', ['id' => $ref->getId()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $articles = json_decode($content, true);
        $this->assertEquals(3, count($articles));
        $article = $articles[0];
        $this->assertEquals(FeedControllerTest::NB_ARTICLE_FIELDS, count($article));
        $this->assertEquals($articleRef->getId(), $article['id']);
        $this->assertEquals($articleRef->getPublicId(), $article['public_id']);
        $this->assertEquals($articleRef->getTitle(), $article['title']);
        $this->assertEquals($articleRef->getLink(), $article['link']);
        $this->assertEquals($articleRef->getLastArticleContent()->getContent(), $article['content']);
        $this->assertEquals($articleRef->getPublicationDate()->format(\DateTime::ISO8601), $article['publication_date']);
        if (! empty($ref->getDateModification())) {
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

        $route =  $this->getUrl('api_1_get_category_articles', ['id' => $ref->getName()]);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $articles = json_decode($content, true);
        $this->assertEquals(3, count($articles));
        $article = $articles[0];
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
}

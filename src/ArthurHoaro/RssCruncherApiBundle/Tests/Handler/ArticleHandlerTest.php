<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Form\FeedType;
use ArthurHoaro\RssCruncherApiBundle\Handler\ArticleHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\FeedHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\UserFeedHandler;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadArticleFeedArray;
use ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ArticleHandlerTest extends WebTestCase {
    /**
     * @var EntityManager
     */
    private $_em;

    /**
     * @var ArticleHandler
     */
    private $articleHandler;

    /**
     * @var UserFeedHandler
     */
    private $feedHandler;

    protected function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->articleHandler = $kernel->getContainer()->get('arthur_hoaro_rss_cruncher_api.article.handler');
        $this->feedHandler = $kernel->getContainer()->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');

        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->loadFixtures($fixtures);
    }

    /**
     * Rollback changes.
     */
    public function tearDown()
    {
    }

    public function testSaveGetNewFullArticle()
    {
        $article = new Article();
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        /** @var UserFeed $feed */
        $feed = $this->feedHandler->get($feed->getId());

        $article->setFeed($feed->getFeed());
        $article->setTitle($title = 'Foo Bar!');
        $article->setAuthorEmail($email = 'john@doe.com');
        $article->setAuthorName($name = 'John Doe');
        $article->setLink($link = 'http://foo.bar');
        $article->setPublicId($publicId = 'http://doe.com/permalink/123');
        $article->setPublicationDate($date = new \DateTime());

        $articleContent = new ArticleContent();
        $articleContent->setArticle($article);
        $articleContent->setDate($date);
        $articleContent->setContent($content = '<div>content</div>');

        $article->addArticleContent($articleContent);

        $article = $this->articleHandler->save($article);
        $id = $article->getId();
        $articleContentId = $article->getLastArticleContent()->getId();

        /** @var Article $article */
        $article = $this->articleHandler->get($id);

        $this->assertEquals($id, $article->getId());
        $this->assertEquals($articleContentId, $article->getLastArticleContent()->getId());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($email, $article->getAuthorEmail());
        $this->assertEquals($name, $article->getAuthorName());
        $this->assertEquals($link, $article->getLink());
        $this->assertEquals($publicId, $article->getPublicId());
        $this->assertEquals($date, $article->getPublicationDate());
        $this->assertEquals($feed->getFeed()->getId(), $article->getFeed()->getId());
        $this->assertEquals($id, $article->getLastArticleContent()->getArticle()->getId());
        $this->assertEquals($content, $article->getLastArticleContent()->getContent());
        $this->assertEquals($date, $article->getLastArticleContent()->getDate());
    }

    public function testSaveGetFullArticleExactCopy()
    {
        $article = new Article();
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        /** @var UserFeed $feed */
        $feed = $this->feedHandler->get($feed->getId());

        $article->setFeed($feed->getFeed());
        $article->setTitle($title = 'Foo Bar!');
        $article->setAuthorEmail($email = 'john@doe.com');
        $article->setAuthorName($name = 'John Doe');
        $article->setLink($link = 'http://foo.bar');
        $article->setPublicId($publicId = 'http://doe.com/permalink/123');
        $article->setPublicationDate($date = new \DateTime());

        $articleContent = new ArticleContent();
        $articleContent->setArticle($article);
        $articleContent->setDate($date);
        $articleContent->setContent($content = '<div>content</div>');

        $article->addArticleContent($articleContent);

        $article = $this->articleHandler->save($article);
        $id = $article->getId();
        $contentId = $article->getLastArticleContent()->getId();
        $article = $this->articleHandler->save($article);

        /** @var Article $article */
        $article = $this->articleHandler->get($id);

        $this->assertEquals($id, $article->getId());
        $this->assertEquals($contentId, $article->getLastArticleContent()->getId());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($email, $article->getAuthorEmail());
        $this->assertEquals($name, $article->getAuthorName());
        $this->assertEquals($link, $article->getLink());
        $this->assertEquals($publicId, $article->getPublicId());
        $this->assertEquals($date, $article->getPublicationDate());
        $this->assertEquals($feed->getFeed()->getId(), $article->getFeed()->getId());
        $this->assertEquals($id, $article->getLastArticleContent()->getArticle()->getId());
        $this->assertEquals($content, $article->getLastArticleContent()->getContent());
        $this->assertEquals($date, $article->getLastArticleContent()->getDate());
    }

    public function testSaveGetMinimalArticle()
    {
        $article = new Article();
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        /** @var UserFeed $feed */
        $feed = $this->feedHandler->get($feed->getId());

        $article->setFeed($feed->getFeed());
        $article->setTitle($title = 'Foo Bar!');
        $article->setLink($link = 'http://foo.bar');
        $article->setPublicationDate($date = new \DateTime());

        $article = $this->articleHandler->save($article);
        $id = $article->getId();
        /** @var Article $article */
        $article = $this->articleHandler->get($id);

        $this->assertEquals($id, $article->getId());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals(null, $article->getAuthorEmail());
        $this->assertEquals(null, $article->getAuthorName());
        $this->assertEquals($link, $article->getLink());
        $this->assertEquals(null, $article->getPublicId());
        $this->assertEquals($date, $article->getPublicationDate());
        $this->assertEquals($feed->getFeed()->getId(), $article->getFeed()->getId());
        $this->assertEquals(null, $article->getLastArticleContent());
    }

    public function testSaveGetArticleNewContent()
    {
        $article = new Article();
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY];
        /** @var UserFeed $feed */
        $feed = $this->feedHandler->get($feed->getId());

        $article->setFeed($feed->getFeed());
        $article->setTitle($title = 'Foo Bar!');
        $article->setLink($link = 'http://foo.bar');
        $article->setPublicId($publicId = 'http://doe.com/permalink/123');
        $article->setPublicationDate($date = new \DateTime());

        $articleContent = new ArticleContent();
        $articleContent->setArticle($article);
        $articleContent->setDate($date);
        $articleContent->setContent($content1 = '<div>content1</div>');

        $article->addArticleContent($articleContent);

        $article = $this->articleHandler->save($article);

        $articleContent = new ArticleContent();
        $articleContent->setArticle($article);
        $articleContent->setDate($date2 = new \DateTime());
        $articleContent->setContent($content2 = '<div>content2</div>');

        $article->addArticleContent($articleContent);

        /** @var Article $article */
        $article = $this->articleHandler->save($article);
        $id = $article->getId();
        $contentId = $article->getLastArticleContent()->getId();
        /** @var Article $article */
        $article = $this->articleHandler->get($id);

        $this->assertEquals($id, $article->getId());
        $this->assertEquals($contentId, $article->getLastArticleContent()->getId());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($link, $article->getLink());
        $this->assertEquals($publicId, $article->getPublicId());
        $this->assertEquals($date, $article->getPublicationDate());
        $this->assertEquals($feed->getFeed()->getId(), $article->getFeed()->getId());
        $this->assertEquals($id, $article->getLastArticleContent()->getArticle()->getId());
        $this->assertEquals($content2, $article->getLastArticleContent()->getContent());
        $this->assertEquals($date2, $article->getLastArticleContent()->getDate());
    }
}

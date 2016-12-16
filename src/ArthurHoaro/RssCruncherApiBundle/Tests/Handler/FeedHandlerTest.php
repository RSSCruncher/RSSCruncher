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

class FeedHandlerTest extends WebTestCase {
    /**
     * @var EntityManager
     */
    private $_em;

    /**
     * @var UserFeedHandler
     */
    private $feedHandler;

    protected function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->feedHandler = $kernel->getContainer()->get('arthur_hoaro_rss_cruncher_api.feed.handler');

        $fixtures = array('ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity\LoadBasicFeedsArticlesData');
        $this->loadFixtures($fixtures);
    }

    /**
     * Rollback changes.
     */
    public function tearDown()
    {
    }

    public function testSelectFeed()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DUMMY]->getFeed();
        /** @var Feed $loaded */
        $loaded = $this->feedHandler->select($feed->getId())[0];

        $this->assertEquals($feed->getId(), $loaded->getId());
        $this->assertEquals($feed->getFeedUrl(), $loaded->getFeedUrl());
        $this->assertEquals($feed->getUserFeeds()[0]->getId(), $loaded->getUserFeeds()[0]->getId());
        $this->assertEquals($feed->getArticles()[0]->getId(), $loaded->getArticles()[0]->getId());
        $this->assertEquals($feed->getDateCreation(), $loaded->getDateCreation());
        $this->assertEquals($feed->getDateFetch(), $loaded->getDateFetch());
        $this->assertEquals($feed->getDateModification(), $loaded->getDateModification());
    }

    public function testSelectDisabledFeed()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DOUBLE_DISABLED]->getFeed();
        $this->assertEmpty($this->feedHandler->select($feed->getId()));
    }

    public function testGetDisabledFeed()
    {
        $feed = LoadBasicFeedsArticlesData::$feeds[LoadArticleFeedArray::DOUBLE_DISABLED]->getFeed();
        $this->assertEmpty($this->feedHandler->get($feed->getId()));
    }
}

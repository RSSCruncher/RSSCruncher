<?php
/**
 * LoadBasicFeedsArticlesData.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\FeedsApiBundle\Entity\Article;
use ArthurHoaro\FeedsApiBundle\Entity\Feed;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBasicFeedsArticlesData implements FixtureInterface {
    static public $articles = array();
    static public $feeds = array();

    public function load(ObjectManager $manager)
    {
        $feed = new Feed();
        $feed->setSitename('sitename');
        $feed->setSiteurl('http://siteurl.tld');
        $feed->setFeedname('feedname');
        $feed->setFeedurl('http://feedurl.tld');

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[LoadArticleFeedArray::DUMMY] = $feed;

        $article = new Article();
        $article->setTitle('article title');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 hour'));
        $article->setModificationDate(new \DateTime('now'));
        $article->setSummary('Bla bla!');
        $article->setContent('Article content... that\'s soooo interesting.');
        $article->setAuthorName('Victor Hugo');
        $article->setAuthorEmail('victor@hu.go');
        $article->setLink('http://dummy.hu.go/article1');
        $article->setFeed($feed);

        $manager->persist($article);
        $manager->flush();

        self::$articles[] = $article;

        $article = new Article();
        $article->setTitle('article2');
        $article->setPublicationDate((new \DateTime('now'))->modify('-4 hour'));
        $article->setContent('Article2 content...');
        $article->setAuthorName('Paul Verlaine');
        $article->setLink('https://verlaine.me/saturne');
        $article->setFeed($feed);

        $manager->persist($article);
        $manager->flush();

        self::$articles[] = $article;

        $article = new Article();
        $article->setTitle('article3');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 day'));
        $article->setContent('Article3 content...');
        $article->setAuthorName('Baudelaire');
        $article->setLink('http://link.io/example');
        $article->setFeed($feed);

        $manager->persist($article);
        $manager->flush();

        self::$articles[] = $article;

        $feed = new Feed();
        $feed->setSitename('hoaro');
        $feed->setSiteurl('http://hoa.ro');
        $feed->setFeedname('hoaro feed');
        $feed->setFeedurl('http://hoa.ro/feed.php?rss');

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[LoadArticleFeedArray::VALID] = $feed;

        $feed = new Feed();
        $feed->setSitename('notparsable');
        $feed->setSiteurl('http://hoa.ro');
        $feed->setFeedname('hoaro not the feed');
        $feed->setFeedurl('http://hoa.ro');

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[LoadArticleFeedArray::NOT_PARSABLE] = $feed;
    }
}

abstract class LoadArticleFeedArray
{
    const DUMMY = 0;
    const VALID = 1;
    const NOT_PARSABLE = 2;
}
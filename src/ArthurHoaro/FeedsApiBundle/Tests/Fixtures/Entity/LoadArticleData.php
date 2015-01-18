<?php
/**
 * LoadArticleData.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\FeedsApiBundle\Entity\Article;
use ArthurHoaro\FeedsApiBundle\Entity\Feed;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArticleData implements FixtureInterface {
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
        self::$feeds[0] = $feed;

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

        self::$articles[0] = $article;

        $feed = new Feed();
        $feed->setSitename('hoaro');
        $feed->setSiteurl('http://hoa.ro');
        $feed->setFeedname('hoaro feed');
        $feed->setFeedurl('http://hoa.ro/feed.php?rss');

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[1] = $feed;
    }
} 
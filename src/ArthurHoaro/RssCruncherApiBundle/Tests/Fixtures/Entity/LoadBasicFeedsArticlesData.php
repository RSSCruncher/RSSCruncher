<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBasicFeedsArticlesData implements FixtureInterface {
    static public $articles = array();
    static public $feeds = array();

    public function load(ObjectManager $manager)
    {
        $feed = new Feed();
        $feed->setFeedurl('https://feedurl.tld');
        $feed->setHttps(true);

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
        $feed->setFeedurl('http://hoa.ro/feed.php?rss');
        $feed->setHttps(false);

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[LoadArticleFeedArray::VALID] = $feed;

        $feed = new Feed();
        $feed->setFeedurl('http://hoa.ro');
        $feed->setHttps(false);

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
<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\User;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBasicFeedsArticlesData implements FixtureInterface {
    /**
     * @var ProxyUser[]
     */
    static public $users = [];

    /**
     * @var UserFeed[]
     */
    static public $feeds = [];

    /**
     * @var Article[]
     */
    static public $articles = [];

    /**
     * @var FeedCategory[]
     */
    static public $categories = [];

    /** FIXME! unreadable */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('dummy');
        $user->setEmail('dummy');
        $user->setPassword('dummy');
        $client = new Client();

        $proxy = new ProxyUser();
        $proxy->setUser($user);
        $proxy->setClient($client);
        $manager->persist($user);
        $manager->persist($client);
        $manager->persist($proxy);

        $feedGroup = new FeedGroup();
        $feedGroup->setName('group1');
        $feedGroup->addProxyUser($proxy);
        $manager->persist($feedGroup);
        $proxy->setFeedGroup($feedGroup);
        $manager->persist($proxy);

        $cat1 = $this->createCategory($manager, 'cat1', $feedGroup);
        $cat1->setDateModification(new \DateTime('+2secs'));
        $cat2 = $this->createCategory($manager, 'cat2', $feedGroup);
        $cat3 = $this->createCategory($manager, 'cat3', $feedGroup);

        $feed = new Feed();
        $feed->setFeedUrl('feedurl.tld/rss');
        $feed->setHttps(true);


        $userFeed = new UserFeed();
        $userFeed->setEnabled(true);
        $userFeed->setSiteName('foo website');
        $userFeed->setSiteUrl('http://foo.com');
        $userFeed->setFeedName('foo feed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20161010'));
        $userFeed->setFeed($feed);
        $userFeed->setFeedGroup($feedGroup);
        $userFeed->setCategory($cat1);
        $feed->addUserFeed($userFeed);
        $manager->persist($feed);
        $manager->persist($userFeed);

        $manager->flush();
        self::$users[] = $proxy;
        self::$feeds[LoadArticleFeedArray::DUMMY] = $userFeed;

        $article = new Article();
        $article->setTitle('article title');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 hour'));
        $article->setModificationDate(new \DateTime('now'));
        $article->setSummary('Bla bla!');
        $article->setAuthorName('Victor Hugo');
        $article->setAuthorEmail('victor@hu.go');
        $article->setLink('http://dummy.hu.go/article1');
        $article->setFeed($feed);
        $feed->addArticle($article);

        $articleContent = new ArticleContent();
        $articleContent->setContent('Article content... that\'s soooo interesting.');
        $articleContent->setDate(new \DateTime());
        $articleContent->setArticle($article);
        $article->addArticleContent($articleContent);
        $manager->persist($article);
        $manager->persist($feed);
        $manager->persist($articleContent);

        $manager->flush();

        self::$articles[0] = $article;

        $article = new Article();
        $article->setTitle('article2');
        $article->setPublicationDate((new \DateTime('now'))->modify('-4 hour'));
        $article->setAuthorName('Paul Verlaine');
        $article->setLink('https://verlaine.me/saturne');
        $article->setFeed($feed);
        $manager->persist($article);

        $articleContent = new ArticleContent();
        $articleContent->setContent('Article2 first content...');
        $articleContent->setDate(new \DateTime('-1 hour'));
        $articleContent->setArticle($article);
        $article->addArticleContent($articleContent);
        $manager->persist($articleContent);

        $articleContent = new ArticleContent();
        $articleContent->setContent('Article2 new content...');
        $articleContent->setDate(new \DateTime());
        $articleContent->setArticle($article);
        $article->addArticleContent($articleContent);
        $manager->persist($articleContent);
        $manager->persist($article);

        $manager->flush();

        self::$articles[1] = $article;

        $article = new Article();
        $article->setTitle('article3');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 day'));
        $article->setAuthorName('Baudelaire');
        $article->setLink('http://link.io/example');
        $article->setFeed($feed);

        $articleContent = new ArticleContent();
        $articleContent->setContent('Article3 content...');
        $articleContent->setDate(new \DateTime());
        $articleContent->setArticle($article);
        $article->addArticleContent($articleContent);
        $manager->persist($article);
        $manager->persist($articleContent);

        $manager->flush();

        self::$articles[2] = $article;

        $feed = new Feed();
        $feed->setFeedUrl('hoa.ro/feed.php?rss');
        $feed->setHttps(false);
        $manager->persist($feed);

        $userFeed = new UserFeed();
        $userFeed->setEnabled(true);
        $userFeed->setSiteName('Hoaro');
        $userFeed->setSiteUrl('http://hoa.ro');
        $userFeed->setFeedName('Hoaro feed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20161011'));
        $userFeed->setFeed($feed);
        $userFeed->setFeedGroup($feedGroup);
        $userFeed->setCategory($cat2);
        $manager->persist($userFeed);

        $manager->flush();
        self::$feeds[LoadArticleFeedArray::VALID] = $userFeed;

        $feed = new Feed();
        $feed->setFeedUrl('hoa.ro');
        $feed->setHttps(false);

        $userFeed = new UserFeed();
        $userFeed->setEnabled(true);
        $userFeed->setSiteName('blop');
        $userFeed->setSiteUrl('http://blop.blip');
        $userFeed->setFeedName('blop feed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20161012'));
        $userFeed->setFeed($feed);
        $userFeed->setFeedGroup($feedGroup);
        $manager->persist($userFeed);

        $manager->persist($feed);
        $manager->flush();
        self::$feeds[LoadArticleFeedArray::NOT_PARSABLE] = $userFeed;

        // Dummy client, just to make sure we don't retrieve its data
        $client = new Client();
        $proxy2 = new ProxyUser();
        $proxy2->setUser($user);
        $proxy2->setClient($client);
        $feedGroup2 = new FeedGroup();
        $feedGroup2->setName('group2');
        $feedGroup2->addProxyUser($proxy2);
        $cat4 = $this->createCategory($manager, 'cat not used', $feedGroup2);
        $proxy2->setFeedGroup($feedGroup2);
        $manager->persist($client);
        $manager->persist($proxy2);
        $manager->persist($feedGroup2);
        $feed = new Feed();
        $feed->setFeedUrl('nope.fr/atom');
        $feed->setHttps(false);
        $manager->persist($feed);
        $userFeed = new UserFeed();
        $userFeed->setEnabled(true);
        $userFeed->setSiteName('Nope');
        $userFeed->setSiteUrl('http://nope.fr');
        $userFeed->setFeedName('Nope feed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20171011'));
        $userFeed->setFeed($feed);
        $userFeed->setFeedGroup($feedGroup2);
        $userFeed->setCategory($cat4);
        $manager->persist($userFeed);
        $article = new Article();
        $article->setTitle('nope article');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 day'));
        $article->setAuthorName('Nopoleon');
        $article->setLink('http://nope.fr/nope-nope-nope');
        $article->setFeed($feed);
        $manager->persist($article);
        $articleContent = new ArticleContent();
        $articleContent->setContent('« Nope »');
        $articleContent->setDate(new \DateTime());
        $articleContent->setArticle($article);
        $manager->persist($articleContent);
        $manager->flush();

        self::$feeds[LoadArticleFeedArray::OTHER_USER] = $userFeed;

        $disabledFeed = new Feed();
        $disabledFeed->setEnabled(false);
        $disabledFeed->setFeedUrl('disabled.fr/atom');
        $disabledFeed->setHttps(false);

        $userFeed = new UserFeed();
        $userFeed->setEnabled(false);
        $userFeed->setSiteName('Disabled');
        $userFeed->setSiteUrl('http://disabled.fr');
        $userFeed->setFeedName('Disabled UserFeed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20161201'));
        $userFeed->setFeed($disabledFeed);
        $userFeed->setFeedGroup($feedGroup);
        $manager->persist($disabledFeed);
        $manager->persist($userFeed);
        $manager->flush();

        self::$feeds[LoadArticleFeedArray::DOUBLE_DISABLED] = $userFeed;

        $userFeed = new UserFeed();
        $userFeed->setEnabled(false);
        $userFeed->setSiteName('Disabled UserFeed-Enabled Feed');
        $userFeed->setSiteUrl('http://disabled.fr');
        $userFeed->setFeedName('Disabled UserFeed');
        $userFeed->setDateCreation(\DateTime::createFromFormat('Ymd', '20161201'));
        $userFeed->setFeed($feed);
        $userFeed->setFeedGroup($feedGroup);
        $manager->persist($userFeed);
        $manager->flush();

        self::$feeds[LoadArticleFeedArray::UFEED_DISABLED] = $userFeed;

        self::$categories = [$cat1, $cat2, $cat3, $cat4];
    }

    protected function createCategory($manager, $name, $feedGroup)
    {
        $cat = new FeedCategory($feedGroup);
        $cat->setFeedGroup($feedGroup);
        $cat->setName($name);
        $cat->setDateCreation(new \DateTime());
        $manager->persist($cat);



        return $cat;
    }
}

interface LoadArticleFeedArray
{
    const DUMMY = 0;
    const VALID = 1;
    const NOT_PARSABLE = 2;
    const OTHER_USER = 3;
    const DOUBLE_DISABLED = 4;
    const UFEED_DISABLED = 5;
}

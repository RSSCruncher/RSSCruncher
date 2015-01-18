<?php
/**
 * LoadArticleData.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\FeedsApiBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArticleData implements FixtureInterface {
    static public $articles = array();

    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('article title');
        $article->setPublicationDate((new \DateTime('now'))->modify('-1 hour'));
        $article->setModificationDate(new \DateTime('now'));
        $article->setSummary('Bla bla!');
        $article->setContent('Article content... that\'s soooo interesting.');
        $article->setAuthorName('Victor Hugo');
        $article->setAuthorEmail('victor@hu.go');
        $article->setLink('http://dummy.hu.go/article1');

        $manager->persist($article);
        $manager->flush();

        self::$articles[] = $article;
    }
} 
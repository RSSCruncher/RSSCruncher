<?php
/**
 * LoadFeedData.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Fixtures\Entity;

use ArthurHoaro\FeedsApiBundle\Entity\Feed;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFeedData implements FixtureInterface {
    static public $feeds = array();

    public function load(ObjectManager $manager)
    {
        $page = new Feed();
        $page->setSitename('sitename');
        $page->setSiteurl('http://siteurl.tld');
        $page->setFeedname('feedname');
        $page->setFeedurl('http://feedurl.tld');

        $manager->persist($page);
        $manager->flush();

        self::$feeds[] = $page;
    }
} 
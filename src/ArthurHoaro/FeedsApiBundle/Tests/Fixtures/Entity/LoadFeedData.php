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
        $feed = new Feed();
        $feed->setSitename('sitename');
        $feed->setSiteurl('http://siteurl.tld');
        $feed->setFeedname('feedname');
        $feed->setFeedurl('http://feedurl.tld');

        $manager->persist($feed);
        $manager->flush();

        self::$feeds[] = $feed;
    }
} 
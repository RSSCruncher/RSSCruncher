<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Helper\FeedHelper;
use ArthurHoaro\RssCruncherApiBundle\Helper\StringUtil;
use ArthurHoaro\RssCruncherApiBundle\Helper\UrlCleaner;
use Doctrine\ORM\EntityRepository;

/**
 * FeedRepository - Custom queries regarding feeds.
 *
 * IMPORTANT: most queries regarding feeds should make sure that the feed is **enabled**.
 */
class FeedRepository extends EntityRepository
{
    /**
     * Find an enabled single feed using a feed URL.
     *
     * FIXME! clean URL?
     *
     * @param string $url URL to look for.
     *
     * @return null|Feed The feed found or null if nothing is found.
     */
    public function findByUrl($url) {
        return $this->findOneBy([
            'feedurl' => $url,
            'enabled' => true,
        ]);
    }

    /**
     * Find an enabled single feed using a feed URL, or create it and save it in DB if it doesn't exist.
     *
     * @param string $url URL to look for.
     *
     * @return Feed Found or newly created Feed.
     */
    public function findByUrlOrCreate($url) {
        $urlObj = new UrlCleaner($url);
        $cleanUrl = $urlObj->cleanup(false);
        $feed = $this->findByUrl($cleanUrl);
        if (! empty($feed)) {
            return $feed;
        }

        return $this->createFeed($cleanUrl, $urlObj->isHttps());
    }

    /**
     * Create a new feed using its URL and save it in DB.
     *
     * @param string $cleanUrl Feed URL.
     * @param string $isHttps  true of the URL can be reached using HTTPS, false otherwise.
     *
     * @return Feed newly created Link.
     */
    private function createFeed($cleanUrl, $isHttps)
    {
        $entity = new Feed();
        $entity->setFeedurl($cleanUrl);
        $entity->setHttps($isHttps);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}

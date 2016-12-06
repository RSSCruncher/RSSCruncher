<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Form\UserFeedType;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use SimplePie;
use Liip\FunctionalTestBundle\Tests\App\Entity\User;
use Symfony\Component\Form\FormInterface;


/**
 * Class FeedHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class FeedHandler extends GenericHandler {

    /**
     * Retrieve an enabled Feed by its ID as an array.
     *
     * @param int   $id     Feed ID.
     * @param array $params Additional parameters.
     *
     * @return array List containing the Feed found or null.
     */
    public function select($id, $params = array()) {
        return $this->repository->findBy(array_merge(
            [
                'id' => $id,
                'enabled' => true,
            ],
            $params
        ));
    }

    /**
     * {@inheritdoc}
     *
     * Only if it's enabled.
     */
    public function get($id)
    {
        $res = $this->select($id);
        if (count($res) == 1) {
            return $res[0];
        }
        return null;
    }

    /**
     * Refresh items of a feed
     *
     * @param Feed   $feed   to refresh.
     * @param SimplePie $reader Service used to fetch feeds.
     *
     * @return Article[] $items List of new Articles.
     *
     * @throws FeedNotFoundException The given Feed doesn't exist in the database.
     * @throws \Exception            DB error.
     */
    public function refreshFeed(Feed $feed, SimplePie $reader) {
        $feedUrl = $feed->isHttps() ? 'https' : 'http';
        $feedUrl .= '://'. $feed->getFeedurl();

        $reader->set_feed_url($feedUrl);
        $reader->set_cache_location($this->cachePath);
        $reader->init();
        if (! empty($reader->error())) {
            throw new \Exception($reader->error());
        }

        $newItems = $reader->get_items();
        $outItems = array();
        foreach ($newItems as $value) {
            $item = ArticleConverter::convertFromRemote($value);
            if ($item->getPublicationDate() > $feed->getDateFetch()
                || $item->getModificationDate() > $feed->getDateFetch()
            ) {
                $item->setFeed($feed);
                $outItems[] = $item;
            }
        }

        return $outItems;
    }

    /**
     * Update the last fetch date.
     *
     * @param Feed $feed to update.
     */
    public function updateDateFetch(Feed $feed)
    {
        $feed->setDateFetch(new \DateTime());
        $this->om->persist($feed);
        $this->om->flush();
    }
}

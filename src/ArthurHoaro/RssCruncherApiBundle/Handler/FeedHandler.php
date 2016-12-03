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
use FeedIo\FeedIo;
use FeedIo\Filter\ModifiedSince;
use Liip\FunctionalTestBundle\Tests\App\Entity\User;
use Symfony\Component\Form\FormInterface;


/**
 * Class FeedHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class FeedHandler extends GenericHandler {



    protected function createFeed($parameters) {

    }

    /**
     * Select a list of Feeds by their IDs
     *
     * @param int $id
     * @return array Feed
     */
    public function selectWithDisabled($id, $params = array()) {
        return $this->repository->findBy(array_merge(['enabled' => false], $params));
    }

    /**
     * @param $id
     * @param array $params
     * @return array
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

//    /**
//     * @param ProxyUser $user
//     * @param int       $limit
//     * @param int       $offset
//     *
//     * @return array
//     */
//    public function all($user, $limit = 5, $offset = 0)
//    {
//        return $this->repository->findByUser($user);
//    }

    /**
     * Refresh items of a feed
     *
     * @param Feed   $feed
     * @param FeedIo $reader
     *
     * @return array $items - list of refreshed feeds
     *
     * @throws FeedNotFoundException
     */
    public function refreshFeed(Feed $feed, FeedIo $reader) {
        return $this->refresh($feed, $reader);
    }

    /**
     * Refresh items of a feed
     *
     * @param Feed $feed
     * @param FeedIo $reader
     * @return array $outItems
     *
     * @throws \Exception
     */
    private function refresh(Feed $feed, FeedIo $reader) {
        $feedUrl = $feed->getFeedurl();
        try {
            $readFeed = $reader->read($feedUrl);
        } catch(\Exception $e) {
            // An ugly trick to handle SimpleXML bad error handling... maybe to be removed
            if(strpos($e->getMessage(), 'parse') !== false) {
                throw new FeedNotParsedException($feedUrl, $e);
            }
            else throw $e;
        }
        $newItems = $readFeed->getFeed();

        $outItems = array();
        foreach($newItems as $value) {
            $item = ArticleConverter::convertFromRemote($value);
            if (true || $item->getModificationDate() > $feed->getDateFetch()) {
                $item->setFeed($feed);
                $outItems[] = $item;
            }
        }

        return $outItems;
    }

    /**
     * Update the last fetch date.
     *
     * @param Feed $feed
     */
    public function updateDateFetch(Feed $feed)
    {
        $feed->setDateFetch(new \DateTime());
        $this->om->persist($feed);
        $this->om->flush();
    }
}

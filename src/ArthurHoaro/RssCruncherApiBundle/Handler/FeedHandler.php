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
use Debril\RssAtomBundle\Protocol\FeedReader;
use Debril\RssAtomBundle\Protocol\FeedIn;
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
     * @param ProxyUser $user
     * @param int       $limit
     * @param int       $offset
     *
     * @return array
     */
    public function all($user, $limit = 5, $offset = 0)
    {
        return $this->repository->findByUser($user);
    }

    /**
     * Refresh items of a feed
     *
     * @param int $id
     * @param FeedReader $reader
     * @return array $items - list of refreshed feeds
     * @throws FeedNotFoundException
     */
    public function refreshFeed($id, FeedReader $reader) {
        if(empty($id)) throw new FeedNotFoundException();

        $feed = $this->select($id);
        if (count($feed) > 0 )  {
            return $this->refresh(array_shift($feed), $reader);
        }
        throw new FeedNotFoundException($id);
    }

    /**
     * Refresh items of a feed
     *
     * @param Feed $feed
     * @param FeedReader $reader
     * @return array $outItems
     *
     * @throws \Exception
     */
    private function refresh(Feed $feed, FeedReader $reader) {
        $feedUrl = $feed->getFeedurl();
        try {
            $readFeed = $reader->getFeedContent($feedUrl);
        } catch(\Exception $e) {
            // An ugly trick to handle SimpleXML bad error handling... maybe to be removed
            if( strpos($e->getMessage(), 'parse') !== false ) {
                throw new FeedNotParsedException($feedUrl, $e);
            }
            else throw $e;
        }
        $newItems = $readFeed->getItems();

        $outItems = array();
        foreach($newItems as $value) {
            $item = ArticleConverter::convertFromRemote($value);
            $item->setFeed($feed);
            $outItems[] = $item;
        }

        return $outItems;
    }
} 
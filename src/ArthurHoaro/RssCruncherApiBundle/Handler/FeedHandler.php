<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Model\IFeed;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use Debril\RssAtomBundle\Protocol\FeedReader;
use Debril\RssAtomBundle\Protocol\FeedIn;
use Liip\FunctionalTestBundle\Tests\App\Entity\User;


/**
 * Class FeedHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class FeedHandler extends GenericHandler {

    /**
     * Select a list of Feeds by their IDs
     *
     * @param int $id
     * @return array IFeed
     */
    public function select($id, $params = array()) {
        return $this->repository->findBy(array_merge(array('id' => $id), $params));
    }

    /**
     * @param $id
     * @param array $params
     * @return array
     */
    public function selectEnabled($id, $params = array()) {
        return $this->select($id, array_merge($params, array('enabled' => true)));
    }

    /**
     * @param ProxyUser $user
     * @param int       $limit
     * @param int       $offset
     *
     * @return array
     */
    public function allUser($user, $limit = 5, $offset = 0)
    {
        //return $this->repository->findBy(['proxyUsers' => $user], null, $limit, $offset);
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
     * @param IFeed $feed
     * @param FeedReader $reader
     * @return array $outItems
     *
     * @throws \Exception
     */
    private function refresh(IFeed $feed, FeedReader $reader) {
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
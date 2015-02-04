<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

use ArthurHoaro\FeedsApiBundle\Form\ArticleType;
use ArthurHoaro\FeedsApiBundle\Helper\ArticleConverter;
use ArthurHoaro\FeedsApiBundle\Model\IFeed;
use ArthurHoaro\FeedsApiBundle\Entity\Article;
use Debril\RssAtomBundle\Protocol\FeedReader;
use Debril\RssAtomBundle\Protocol\FeedIn;


/**
 * Class FeedHandler
 * @package ArthurHoaro\FeedsApiBundle\Handler
 */
class FeedHandler extends GenericHandler {

    /**
     * Select a list of Feeds by their IDs
     *
     * @param array $id
     * @return array IFeed
     */
    public function select(array $id) {
        return $this->repository->findBy(array('id' => $id));
    }

    /**
     * Refresh items of a list of feeds
     *
     * @param array $id
     * @param FeedReader $reader
     * @return array $items - list of refreshed feeds
     */
    public function refreshFeeds(array $id, FeedReader $reader) {
        if( !empty($id) )
            $feeds = $this->select($id);
        else
            $feeds = $this->all();

        $items = array();
        foreach($feeds as $key => $value) {
            $items[] = $this->refresh($value, $reader);
        }
        return $items;
    }

    /**
     * Refresh items of a feed
     *
     * @param IFeed $feed
     * @param FeedReader $reader
     * @return array $outItems -
     */
    private function refresh(IFeed $feed, FeedReader $reader) {
        $readFeed = $reader->getFeedContent($feed->getFeedurl());
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
<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

use ArthurHoaro\FeedsApiBundle\Form\ArticleType;
use ArthurHoaro\FeedsApiBundle\Helper\ArticleConverter;
use ArthurHoaro\FeedsApiBundle\Model\IFeed;
use ArthurHoaro\FeedsApiBundle\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

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
     */
    public function refreshFeeds(array $id, $reader) {
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
     */
    private function refresh(IFeed $feed, $reader) {
        $readFeed = $reader->getFeedContent($feed->getFeedurl());
        $newItems = $readFeed->getItems();
        var_dump($newItems);die;
        $outItems = array();
        foreach($newItems as $value) {
            $item = ArticleConverter::convert($value);
            $item->setFeed($feed);

            $outItems[] = $this->processForm($item, array(), 'PUT', get_class(new ArticleType()));
        }

        return $outItems;
    }
} 
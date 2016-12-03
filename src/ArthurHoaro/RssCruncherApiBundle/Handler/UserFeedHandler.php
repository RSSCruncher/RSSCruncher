<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Form\UserFeedType;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use Liip\FunctionalTestBundle\Tests\App\Entity\User;


/**
 * Class FeedHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class UserFeedHandler extends GenericHandler {
    public function post(array $parameters) {
        /** @var UserFeed $entity */
        $entity = $this->processForm($this->create(), $parameters, 'POST');

        /** @var FeedRepository $feedRepository */
        $feedRepository = $this->om->getRepository(Feed::class);
        // Retrieve or create the existing Feed matching our feedurl.
        $feed = $feedRepository->findByUrlOrCreate($parameters['feedurl']);

        // Attach the feed
        $entity->setFeed($feed);
        return $entity;
    }
} 
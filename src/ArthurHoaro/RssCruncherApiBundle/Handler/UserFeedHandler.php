<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotParsedException;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use Debril\RssAtomBundle\Protocol\FeedReader;
use Debril\RssAtomBundle\Protocol\FeedIn;
use Liip\FunctionalTestBundle\Tests\App\Entity\User;


/**
 * Class FeedHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class UserFeedHandler extends GenericHandler {
    public function post(array $parameters)
    {
        $proxyUser = $parameters['proxyUser'];
        unset($parameters['proxyUser']);

        /** @var UserFeed $entity */
        $entity = $this->processForm($this->create(), $parameters, 'POST');

        $entity->setProxyUser($proxyUser);

        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }
} 
<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedExistsException;
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

    /**
     * @var ProxyUser
     */
    protected $proxyUser;

    /**
     * Handle POST data to create a valid UserFeed.
     *
     * @param array $parameters POST data
     *
     * @return UserFeed with a valid feed atteched.
     *
     * @throws \Exception ProxyUser must be set.
     */
    public function post(array $parameters) {
        if (empty($this->proxyUser)) {
            throw new \Exception('ProxyUser must be set to create a new UserFeed.');
        }

        /** @var UserFeed $entity */
        $entity = $this->processForm($this->create(), $parameters, 'POST');

        /** @var FeedRepository $feedRepository */
        $feedRepository = $this->om->getRepository(Feed::class);
        // Retrieve or create the existing Feed matching our feedurl.
        $feed = $feedRepository->findByUrlOrCreate($parameters['feedurl']);

        $userFeed = $this->repository->findOneBy([
            'feed' => $feed->getId(),
            'proxyUser' => $this->proxyUser->getId(),
        ]);
        if (! empty($userFeed)) {
            throw new FeedExistsException($userFeed);
        }

        $entity->setFeed($feed);
        $entity->setProxyUser($this->proxyUser);
        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * @return ProxyUser
     */
    public function getProxyUser(): ProxyUser
    {
        return $this->proxyUser;
    }

    /**
     * @param ProxyUser $proxyUser
     */
    public function setProxyUser(ProxyUser $proxyUser)
    {
        $this->proxyUser = $proxyUser;
    }
}

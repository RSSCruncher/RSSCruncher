<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;
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
use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
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
        // Retrieve or create the existing Feed matching our feedUrl.
        $feed = $feedRepository->findByUrlOrCreate($parameters['feed_url']);

        if (! empty($parameters['category'])) {
            /** @var FeedRepository $feedRepository */
            $categoryRepo = $this->om->getRepository(FeedCategory::class);
            // Retrieve or create the existing Feed matching our feedUrl.
            $category = $categoryRepo->findByNameOrCreate($parameters['category'], $this->proxyUser->getFeedGroup());
        }

        $userFeed = $this->repository->findOneBy([
            'feed' => $feed->getId(),
            'feedGroup' => $this->proxyUser->getFeedGroup()->getId(),
        ]);
        if (! empty($userFeed)) {
            throw new FeedExistsException($userFeed);
        }

        $entity->setFeed($feed);
        $entity->setFeedGroup($this->proxyUser->getFeedGroup());
        if (! empty($category)) {
            $entity->setCategory($category);
        }
        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Edit an Entity and return after update.
     *
     * @param IEntity $entity
     * @param array   $parameters
     *
     * @return IEntity
     */
    public function put(IEntity $entity, array $parameters)
    {
        $entity = $this->processForm($entity, $parameters, 'PUT');
        $entity->setDateModification(new \DateTime());

        /** @var FeedRepository $feedRepository */
        $feedRepository = $this->om->getRepository(Feed::class);
        // Retrieve or create the existing Feed matching our feedUrl.
        $feed = $feedRepository->findByUrlOrCreate($parameters['feed_url']);

        $entity->setFeed($feed);
        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Partially update a Entity.
     *
     * @param IEntity $entity
     * @param array         $parameters
     *
     * @return IEntity
     */
    public function patch(IEntity $entity, array $parameters)
    {
        if (empty($parameters['feed_url'])) {
            $parameters['feed_url'] = $entity->getFeed()->getFeedurl();
        }
        $entity = $this->processForm($entity, $parameters, 'PATCH');

        /** @var FeedRepository $feedRepository */
        $feedRepository = $this->om->getRepository(Feed::class);
        // Retrieve or create the existing Feed matching our feedUrl.
        $feed = $feedRepository->findByUrlOrCreate($parameters['feed_url']);
        $entity->setFeed($feed);


        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Retrieve an enabled UserFeed by its ID as an array.
     *
     * @param int   $id     Feed ID.
     * @param array $params Additional parameters.
     *
     * @return UserFeed List containing the Feed found or null.
     */
    public function get($id, $params = []) {
        return $this->repository->findOneBy(array_merge(
            [
                'id' => $id,
                'enabled' => true,
            ],
            $params
        ));
    }

    /**
     * Disable UserFeed.
     *
     * @param UserFeed $entity
     *
     * @return UserFeed
     */
    public function disable(UserFeed $entity)
    {
        $entity->setEnabled(false);
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

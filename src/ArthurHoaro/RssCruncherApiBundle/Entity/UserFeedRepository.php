<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use Doctrine\ORM\EntityRepository;

/**
 * Class UserFeedRepository
 *
 * Custom queries regarding UserFeed.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Entity
 */
class UserFeedRepository extends EntityRepository
{
    /**
     * Retrieve a list of UserFeeds associated to a ProxyUser.
     *
     * FIXME! limit and offset are ignored.
     *
     * @param ProxyUser $proxyUser to look for.
     * @param int       $limit     Max elements to retrieve.
     * @param int       $offset    Retrieve elements from item $offset.
     *
     * @return UserFeed[]
     */
    public function findByProxyUser($proxyUser, $limit = 0, $offset = 0)
    {
        $dql  = 'SELECT f FROM ArthurHoaroRssCruncherApiBundle:UserFeed f ';
        $dql .= 'JOIN f.proxyUsers pu ';
        $dql .= 'WHERE pu.client = :client AND pu.user = :user ';
        $dql .= 'AND enabled = true ';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('client', $proxyUser->getClient());
        $query->setParameter('user', $proxyUser->getUser());
        return $query->getResult();
    }
}

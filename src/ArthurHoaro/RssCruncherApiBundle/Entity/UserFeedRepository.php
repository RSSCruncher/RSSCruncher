<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
        $qb = $this->_em->createQueryBuilder();
        $qb->select('uf')
            ->from('ArthurHoaroRssCruncherApiBundle:UserFeed', 'uf')
            ->join('uf.feedGroup', 'fg')
            ->join('fg.proxyUsers', 'pu')
            ->join('uf.feed', 'f')
            ->where('pu.client = :client AND pu.user = :user')
            ->andWhere('uf.enabled = :enabled and f.enabled = :enabled')
            ->orderBy('uf.dateCreation', 'DESC')
            ->setParameter('client', $proxyUser->getClient())
            ->setParameter('user', $proxyUser->getUser())
            ->setParameter('enabled', true)
            ->setFirstResult($offset);

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()->getResult();
    }
}

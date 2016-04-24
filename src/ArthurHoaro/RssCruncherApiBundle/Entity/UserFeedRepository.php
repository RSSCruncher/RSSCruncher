<?php
/**
 * UserFeedRepository.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use Doctrine\ORM\EntityRepository;

class UserFeedRepository extends EntityRepository
{
    /**
     * @param ProxyUser $proxyUser
     * @param array     $options
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
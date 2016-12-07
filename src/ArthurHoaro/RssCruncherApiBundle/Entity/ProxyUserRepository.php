<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\AccessToken;

/**
 * Class ProxyUserRepository
 *
 * Custom queries regarding ProxyUser.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Entity
 */
class ProxyUserRepository extends EntityRepository
{
    /**
     * Find a ProxyUser using its unique couple Client+User.
     *
     * @param User   $user
     * @param Client $client
     *
     * @return ProxyUser|null Found ProxyUser or null if none has been found.
     */
    public function findByUserClient(User $user, Client $client)
    {
        $dql  = 'SELECT pu FROM ArthurHoaroRssCruncherApiBundle:ProxyUser pu ';
        $dql .= 'WHERE pu.client = :client AND pu.user = :user';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('client', $client);
        $query->setParameter('user', $user);

        return $query->getSingleResult();
    }
}

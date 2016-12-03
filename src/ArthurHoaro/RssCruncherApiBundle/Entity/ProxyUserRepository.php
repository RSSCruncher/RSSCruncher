<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


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
     * Find a ProxyUser using its AccessToken.
     *
     * @param AccessToken $accessToken
     *
     * @return ProxyUser|null Found ProxyUser or null if none has been found.
     */
    public function findByToken($accessToken)
    {
        $dql = 'SELECT pu FROM ProxyUser pu WHERE client = :client AND user = :user';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('client', $accessToken->getClient());
        $query->setParameter('user', $accessToken->getUser());

        return $query->getSingleResult();
    }
}

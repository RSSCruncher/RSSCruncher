<?php
/**
 * ProxyUserRepository.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use Doctrine\ORM\EntityRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\AccessToken;

class ProxyUserRepository extends EntityRepository
{
    /**
     * @param AccessToken $accessToken
     * @return array
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
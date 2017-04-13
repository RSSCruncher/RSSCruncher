<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ReadArticleRepository extends EntityRepository
{
    /**
     * @param int       $id
     * @param ProxyUser $proxyUser
     *
     * @return ReadArticle
     */
    public function findOneByArticle($id, $proxyUser)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ra')
            ->from('ArthurHoaroRssCruncherApiBundle:ReadArticle', 'ra')
            ->join('ra.article', 'a')
            ->join('ra.userFeed', 'uf')
            ->where('a.id = :id')
            ->andWhere('uf.feedGroup = :feedGroup')
            ->andWhere('uf.enabled = :enabled')
            ->setParameter('id', $id)
            ->setParameter('feedGroup', $proxyUser->getFeedGroup())
            ->setParameter('enabled', true);
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
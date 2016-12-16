<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

/**
 * Class FeedCategoryRepository
 *
 * Custom queries regarding FeedCategory.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Entity
 */
class FeedCategoryRepository extends EntityRepository
{
    /**
     * Find an enabled single FeedCategory using its aname,
     * or create it and save it in DB if it doesn't exist.
     *
     * @param string    $name      to look for.
     * @param FeedGroup $feedGroup
     *
     * @return FeedCategory Found or newly created FeedCategory.
     */
    public function findByNameOrCreate($name, $feedGroup) {
        $cat = $this->findOneBy([
            'name' => $name,
            'feedGroup' => $feedGroup
        ]);
        if (! empty($cat)) {
            return $cat;
        }

        return $this->createFeedCategory($name, $feedGroup);
    }

    /**
     * Create a new category using its name and save it in DB.
     *
     * @param string $name category name.
     * @param FeedGroup $feedGroup
     *
     * @return FeedCategory newly created FeedCategory.
     */
    private function createFeedCategory($name, $feedGroup)
    {
        $entity = new FeedCategory();
        $entity->setName($name);
        $entity->setFeedGroup($feedGroup);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param $category
     *
     * @return UserFeed[]
     */
    public function findUserFeedArticles($category)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('uf, f, a')
            ->from('ArthurHoaroRssCruncherApiBundle:UserFeed', 'uf')
            ->join('uf.feed', 'f')
            ->join('f.articles', 'a')
            ->where('uf.category = :category')
            ->andWhere('uf.enabled = :enabled and f.enabled = :enabled')
            ->orderBy('a.publicationDate', 'DESC')
            ->setParameter('category', $category)
            ->setParameter('enabled', true);
        return $qb->getQuery()->getResult();
    }
}

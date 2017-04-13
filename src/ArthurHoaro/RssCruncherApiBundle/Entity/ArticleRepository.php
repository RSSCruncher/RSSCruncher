<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ArticleRepository
 *
 * Custom queries regarding articles:
 *   - Find an existing article using a new item
 */
class ArticleRepository extends EntityRepository
{
    /**
     * Find if this Article already exists for a Feed:
     *  - a Feed must be defined
     *  - first attempt on publicId
     *  - second attempt on link
     *
     * @param Article $article
     *
     * @return Article article found | null if not found
     */
    public function findExistingArticle(Article $article) {
        if (empty($article->getFeed())
            || (empty($article->getPublicId()) && empty($article->getLink()))
        ) {
            return null;
        }

        $dql = 'SELECT a FROM ArthurHoaro\RssCruncherApiBundle\Entity\Article a WHERE a.feed = :feed AND ';

        if (! empty($article->getPublicId())) {
            $query = $this->_em->createQuery($dql . 'a.publicId = :publicId');
            $query->setParameter('publicId', $article->getPublicId());
        }
        else {
            $query = $this->_em->createQuery($dql. 'a.link = :link');
            $query->setParameter('link', $article->getLink());
        }

        $query->setParameter('feed', $article->getFeed());
        /** @var Article[] $results */
        $results = $query->getResult();

        if (! empty($results) && count($results) == 1
            && is_a($results[0], 'ArthurHoaro\RssCruncherApiBundle\Entity\Article')
        ) {
            return $results[0];
        }
        return null;
    }

    /**
     * @param FeedGroup $feedGroup
     * @param int       $offset
     * @param int       $limit
     *
     * @return UserFeed[]
     */
    public function findUserFeedArticles($feedGroup, $offset = 0, $limit = 0)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('uf, f, a, ar')
            ->from('ArthurHoaroRssCruncherApiBundle:UserFeed', 'uf')
            ->join('uf.feed', 'f')
            ->join('f.articles', 'a')
            ->leftJoin('a.readArticles', 'ar')
            ->where('uf.feedGroup = :feedGroup')
            ->andWhere('uf.enabled = :enabled and f.enabled = :enabled')
            ->orderBy('a.publicationDate', 'DESC')
            ->setParameter('feedGroup', $feedGroup)
            ->setParameter('enabled', true)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int       $id
     * @param FeedGroup $feedGroup
     *
     * @return UserFeed[]
     */
    public function findArticle($id, $feedGroup)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('a, f, uf')
            ->from('ArthurHoaroRssCruncherApiBundle:Article', 'a')
            ->join('a.feed', 'f')
            ->join('f.userFeeds', 'uf')
            ->where('a.id = :id')
            ->andWhere('uf.feedGroup = :feedGroup')
            ->andWhere('uf.enabled = :enabled and f.enabled = :enabled')
            ->setParameter('id', $id)
            ->setParameter('feedGroup', $feedGroup)
            ->setParameter('enabled', true);
        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}

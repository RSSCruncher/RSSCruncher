<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
}

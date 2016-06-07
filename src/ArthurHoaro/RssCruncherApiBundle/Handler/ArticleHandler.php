<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleRepository;

/**
 * Class ArticleHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class ArticleHandler extends GenericHandler {

    /**
     * Insert or Update an Article
     *
     * @param Article $article
     * @return Article
     */
    public function save(Article $article) {

        $existing = $this->repository->findExistingArticle($article);
        if( $existing === null ) {
            $this->om->persist($article);
            $this->om->flush($article);
        }
        else {
            $article = ArticleConverter::convertFromPrevious($existing, $article);
            $this->om->persist($article);
            $this->om->flush($article);
        }

        return $article;
    }
} 
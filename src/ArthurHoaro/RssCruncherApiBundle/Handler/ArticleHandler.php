<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Model\IArticle;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleRepository;

/**
 * Class ArticleHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class ArticleHandler extends GenericHandler {

    /**
     * Insert or Update an Article
     *
     * @param IArticle $article
     * @return IArticle
     */
    public function save(IArticle $article) {

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
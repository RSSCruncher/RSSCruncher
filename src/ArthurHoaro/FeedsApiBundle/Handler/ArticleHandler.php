<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

use ArthurHoaro\FeedsApiBundle\Helper\ArticleConverter;
use ArthurHoaro\FeedsApiBundle\Model\IArticle;
use ArthurHoaro\FeedsApiBundle\Entity\ArticleRepository;

/**
 * Class ArticleHandler
 * @package ArthurHoaro\FeedsApiBundle\Handler
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
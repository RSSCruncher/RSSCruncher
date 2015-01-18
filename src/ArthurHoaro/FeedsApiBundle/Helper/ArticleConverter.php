<?php
/**
 * ArticleConverter.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Helper;


use ArthurHoaro\FeedsApiBundle\Entity\Article;
use Debril\RssAtomBundle\Protocol\ItemOut;

class ArticleConverter {
    public static function convert(ItemOut $originalArticle) {
        $convertedArticle = new Article();
        $convertedArticle->setTitle( $originalArticle->getTitle() );
        $convertedArticle->setLink( $originalArticle->getLink() );
        $convertedArticle->setSummary( $originalArticle->getSummary() );
        $convertedArticle->setContent( $originalArticle->getDescription() );
        $convertedArticle->setPublicationDate( $originalArticle->getUpdated() );
        $convertedArticle->setAuthorName( $originalArticle->getAuthor() );
        return $convertedArticle;
    }
} 
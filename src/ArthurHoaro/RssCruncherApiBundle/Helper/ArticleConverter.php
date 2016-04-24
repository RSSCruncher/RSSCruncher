<?php
/**
 * ArticleConverter.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Helper;


use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use Debril\RssAtomBundle\Protocol\ItemOut;

/**
 * Class ArticleConverter
 * @package ArthurHoaro\RssCruncherApiBundle\Helper
 */
class ArticleConverter {
    /**
     * Convert an ItemOut to an Article
     *
     * @param ItemOut $originalArticle
     * @return Article
     */
    public static function convertFromRemote(ItemOut $originalArticle) {
        $convertedArticle = new Article();
        $convertedArticle->setTitle( (string) $originalArticle->getTitle() );
        $convertedArticle->setLink( (string) $originalArticle->getLink() );
        $convertedArticle->setSummary( (string) $originalArticle->getSummary() );
        $convertedArticle->setContent( (string) $originalArticle->getDescription() );
        $convertedArticle->setPublicationDate( $originalArticle->getUpdated() );
        $convertedArticle->setAuthorName( (string) $originalArticle->getAuthor() );
        $convertedArticle->setPublicId( (empty($originalArticle->getPublicId())) ? (int) $originalArticle->getPublicId() : null );
        return $convertedArticle;
    }

    /**
     * Update Article from its newer version
     *
     * @param Article $previous
     * @param Article $updated
     * @param bool $clearEmpty (default: false) - set to true to erase old data if newer is blank
     * @return Article - previous updated
     */
    public static function convertFromPrevious(Article $previous, Article $updated, $clearEmpty = false) {
        if( !empty($updated->getTitle()) || $clearEmpty )
            $previous->setTitle($updated->getTitle());
        if( !empty($updated->getLink()) || $clearEmpty )
            $previous->setLink($updated->getLink());
        if( !empty($updated->getSummary()) || $clearEmpty )
            $previous->setSummary($updated->getSummary());
        if( !empty($updated->getContent()) || $clearEmpty )
            $previous->setContent($updated->getContent());
        if( !empty($updated->getModificationDate()) || $clearEmpty )
            $previous->setModificationDate($updated->getModificationDate());
        if( !empty($updated->getAuthorName()) || $clearEmpty )
            $previous->setAuthorName($updated->getAuthorName());

        return $previous;
    }
} 
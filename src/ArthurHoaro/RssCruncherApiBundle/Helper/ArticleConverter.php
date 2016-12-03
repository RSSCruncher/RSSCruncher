<?php

namespace ArthurHoaro\RssCruncherApiBundle\Helper;


use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;
use FeedIo\Feed\Item;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class ArticleConverter
 * @package ArthurHoaro\RssCruncherApiBundle\Helper
 */
class ArticleConverter {

    /**
     * Convert an Item to an Article
     *
     * @param Item $originalArticle
     *
     * @return Article
     */
    public static function convertFromRemote(Item $originalArticle) {
        $convertedArticle = new Article();
        foreach ($originalArticle->getAllElements() as $element) {
            switch ($element->getName()) {
                case 'published':
                    $dt = \DateTime::createFromFormat(\DateTime::ISO8601, $element->getValue());
                    $convertedArticle->setPublicationDate($dt);
                    break;
                case 'author':
                    $convertedArticle->setAuthorName($element->getValue());
                    break;
            }
        }
        $convertedArticle->setTitle($originalArticle->getTitle());
        $convertedArticle->setLink($originalArticle->getLink());
        $convertedArticle->setModificationDate($originalArticle->getLastModified());
        $id = ! empty($originalArticle->getPublicId()) ? $originalArticle->getPublicId() : null;
        $convertedArticle->setPublicId($id);

        $content = new ArticleContent();
        $content->setArticle($convertedArticle);
        $content->setDate(new \DateTime());
        $content->setContent($originalArticle->getDescription());
        $convertedArticle->addArticleContent($content);

        return $convertedArticle;
    }

    /**
     * Update Article from its newer version
     *
     * @param Article $previous
     * @param Article $updated
     * @param bool $clearEmpty (default: false) - set to true to erase old data if newer is blank
     *
     * @return Article - previous updated
     */
    public static function convertFromPrevious(Article $previous, Article $updated, $clearEmpty = false) {
        if (! empty($updated->getTitle()) || $clearEmpty) {
            $previous->setTitle($updated->getTitle());
        }
        if (! empty($updated->getLink()) || $clearEmpty) {
            $previous->setLink($updated->getLink());
        }
        if (! empty($updated->getSummary()) || $clearEmpty) {
            $previous->setSummary($updated->getSummary());
        }
        if (! empty($updated->getModificationDate()) || $clearEmpty) {
            $previous->setModificationDate($updated->getModificationDate());
        }
        if (! empty($updated->getAuthorName()) || $clearEmpty) {
            $previous->setAuthorName($updated->getAuthorName());
        }

        // If empty content and erase => save blank
        if (empty($updated->getLastArticleContent()) && $clearEmpty) {
            $content = new ArticleContent();
            $content->setContent(null);
            $content->setDate(new \DateTime());
            $content->setArticle($previous);
            $previous->addArticleContent($content);
        }
        // If content different, save new content.
        else if (
            empty($previous->getLastArticleContent())
            || $updated->getLastArticleContent()->getContent() !== $previous->getLastArticleContent()->getContent()
        ) {
            $ac = $updated->getLastArticleContent();
            $ac->setArticle($previous);
            $previous->addArticleContent($ac);
        }

        return $previous;
    }
}

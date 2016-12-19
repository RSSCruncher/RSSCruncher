<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;
use ArthurHoaro\RssCruncherApiBundle\Helper\ArticleConverter;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleRepository;

/**
 * Class ArticleHandler
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class ArticleHandler extends GenericHandler {

    /**
     * @var FeedGroup
     */
    protected $feedGroup;

    /**
     * Insert or Update an Article
     *
     * Note: this will also insert an entry in ArticleContent if the content has to be created or updated.
     *
     * @param Article $article
     * @return Article
     */
    public function save(Article $article) {
        $existing = $this->repository->findExistingArticle($article);
        if ($existing === null) {
            $this->om->persist($article);
            $this->om->flush();
        }
        else {
            $article = ArticleConverter::convertFromPrevious($existing, $article);
            $this->om->persist($article);
            foreach ($article->getArticleContents() as $ac) {
                $this->om->persist($ac);
            }
            $this->om->flush();
        }

        return $article;
    }

    /**
     * Retrieve an enabled UserFeed by its ID as an array.
     *
     * @param int   $id     Feed ID.
     * @param array $params Additional parameters.
     *
     * @return Article List containing the Feed found or null.
     *
     * @throws \Exception
     */
    public function get($id, $params = []) {
        if (empty($this->feedGroup)) {
            throw new \Exception('FeedGroup must be set to get an Article by ID.');
        }

        return $this->repository->findArticle($id, $this->feedGroup);
    }

    /**
     * Get the FeedGroup.
     *
     * @return FeedGroup
     */
    public function getFeedGroup()
    {
        return $this->feedGroup;
    }

    /**
     * Set the FeedGroup.
     *
     * @param FeedGroup $feedGroup
     *
     * @return ArticleHandler
     */
    public function setFeedGroup($feedGroup)
    {
        $this->feedGroup = $feedGroup;

        return $this;
    }
}

<?php


namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;


use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;

class ArticleHistoryDTO implements IApiEntity
{
    /**
     * @var ArticleDTO
     */
    protected $article;

    /**
     * @var ArticleContent[]
     */
    protected $history;

    /**
     * @param ArticleContent $entity
     *
     * @return ArticleHistoryDTO
     */
    public function setEntity($entity)
    {
        return $this;
    }

    /**
     * Get the Article.
     *
     * @return ArticleDTO
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set the Article.
     *
     * @param ArticleDTO $article
     *
     * @return ArticleHistoryDTO
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get the History.
     *
     * @return ArticleContent[]
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set the History.
     *
     * @param ArticleContent[] $history
     *
     * @return ArticleHistoryDTO
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }
}

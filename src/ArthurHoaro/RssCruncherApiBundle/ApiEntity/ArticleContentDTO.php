<?php


namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;


use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;

class ArticleContentDTO implements IApiEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $date;

    /**
     * @param ArticleContent $entity
     *
     * @return ArticleContentDTO
     */
    public function setEntity($entity)
    {
        $this->setId($entity->getId());
        $this->setContent($entity->getContent());
        $this->setDate($entity->getDate());

        return $this;
    }

    /**
     * Get the Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @param int $id
     *
     * @return ArticleContentDTO
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the Content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the Content.
     *
     * @param string $content
     *
     * @return ArticleContentDTO
     */
    public function setContent($content)
    {
        $this->content = ! empty($content) ? $content : '';

        return $this;
    }

    /**
     * Get the Date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the Date.
     *
     * @param \DateTime $date
     *
     * @return ArticleContentDTO
     */
    public function setDate($date)
    {
        $this->date = $date->format(\DateTime::ISO8601);

        return $this;
    }
}

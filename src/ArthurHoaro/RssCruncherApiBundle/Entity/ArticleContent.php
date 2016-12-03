<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * ArticleContent
 *
 * This table represents the history of an item content (description).
 * The latest description of an article will be its content,
 * but this allows the history to be saved, and eventually retrieved.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\ArticleRepository")
 *
 * @ExclusionPolicy("none")
 */
class ArticleContent implements IEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="article_contents")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false)
     *
     * @Exclude
     */
    protected $article;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;


    /**
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}

<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * Article
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="feed_public_id", columns={"feed_id", "public_id"})})
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\ArticleRepository")
 *
 * @ExclusionPolicy("none")
 */
class Article implements IEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="public_id", type="text", nullable=true, unique=true)
     */
    private $publicId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publicationDate", type="datetime")
     */
    private $publicationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationDate", type="datetime", nullable=true)
     */
    private $modificationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    private $summary;

    /**
     * @var ArticleContent[]
     *
     * @ORM\OneToMany(targetEntity="ArticleContent", mappedBy="article", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"date" = "ASC"})
     *
     * @Exclude
     */
    protected $articleContents;

    /**
     * @var string Last article content (API rendering).
     */
    protected $articleContent;

    /**
     * @var string
     *
     * @ORM\Column(name="authorName", type="string", length=255, nullable=true)
     */
    private $authorName;

    /**
     * @var string
     *
     * @ORM\Column(name="authorEmail", type="string", length=255, nullable=true)
     */
    private $authorEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="Feed", inversedBy="articles")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id", nullable=false)
     *
     * @Exclude
     */
    protected $feed;

    /**
     * @ORM\Column(name="feed_id", type="integer")
     */
    protected $feedId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get public id
     *
     * @return int
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * Set public id
     *
     * @param int $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     * @return Article
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime 
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return Article
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime 
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Article
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return ArticleContent
     */
    public function getArticleContents()
    {
        return $this->articleContents;
    }

    /**
     * @return String
     */
    public function getArticleContent()
    {
        $ac = $this->getLastArticleContent();
        if (! empty($ac)) {
            return $ac->getContent();
        }
        return null;
    }

    /**
     * @return ArticleContent
     */
    public function getLastArticleContent()
    {
        if (count($this->articleContents) > 0) {
            return $this->articleContents[count($this->articleContents) - 1];
        }
        return null;
    }

    /**
     * @param ArticleContent $articleContents
     */
    public function setArticleContents($articleContents)
    {
        $this->articleContents = $articleContents;
    }

    /**
     * @param ArticleContent $articleContent
     */
    public function addArticleContent($articleContent)
    {
        $this->articleContents[] = $articleContent;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return Article
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return Article
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Article
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
    }

    /**
     * @return integer
     */
    public function getFeedId()
    {
        return $this->feedId;
    }
}
